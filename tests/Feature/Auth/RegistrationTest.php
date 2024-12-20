<?php

use App\Models\User;
use Laravel\Jetstream\Jetstream;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

beforeEach(function() {
    // Clean up any existing test user
    User::where('email', 'zcrroll@gmail.com')->delete();
    
    $this->testData = [
        'name' => 'hamid chakkouri',
        'email' => 'zcrroll@gmail.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? true : null,
    ];
});

describe('User Registration', function() {
    it('can create a new user', function() {
        $action = new CreateNewUser();
        
        $user = $action->create($this->testData);
        
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($this->testData['name'], $user->name);
        $this->assertEquals($this->testData['email'], $user->email);
        $this->assertTrue(Hash::check($this->testData['password'], $user->password));
    });

    it('validates required fields', function() {
        $action = new CreateNewUser();
        
        try {
            $action->create([]);
            $this->fail('Validation should have failed.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $this->assertArrayHasKey('name', $errors);
            $this->assertArrayHasKey('email', $errors);
            $this->assertArrayHasKey('password', $errors);
        }
    });

    it('validates email uniqueness', function() {
        $action = new CreateNewUser();
        
        // First create a user
        $action->create($this->testData);
        
        // Verify the user exists in the database
        $this->assertDatabaseHas('users', [
            'email' => $this->testData['email']
        ]);
        
        // Try to create another user with the same email
        try {
            $action->create($this->testData);
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
            $this->assertTrue(in_array(
                trans('validation.unique', ['attribute' => 'email']),
                $e->errors()['email']
            ));
        }
    });
});

describe('Password Validation Rules', function() {
    it('enforces password confirmation', function() {
        $action = new CreateNewUser();
        
        $data = $this->testData;
        $data['password'] = 'Password123!';
        $data['password_confirmation'] = 'DifferentPassword123!';
        
        try {
            $action->create($data);
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('password', $e->errors());
            $this->assertTrue(in_array(
                trans('validation.confirmed', ['attribute' => 'password']),
                $e->errors()['password']
            ));
        }
    });

    it('enforces password complexity rules', function() {
        $action = new CreateNewUser();
        
        $data = $this->testData;
        $data['password'] = 'weak';
        $data['password_confirmation'] = 'weak';
        
        try {
            $action->create($data);
            $this->fail('Validation should have failed.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('password', $e->errors());
        }
    });

    it('accepts valid passwords', function() {
        $action = new CreateNewUser();
        
        $data = $this->testData;
        $data['password'] = 'StrongPassword123!';
        $data['password_confirmation'] = 'StrongPassword123!';
        
        $user = $action->create($data);
        
        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue(Hash::check('StrongPassword123!', $user->password));
    });
});
