<?php

use App\Models\User;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    // Find the existing user
    $this->user = User::where('email', 'zcrroll@gmail.com')->first();
    
    // If user doesn't exist in test DB, create it
    if (!$this->user) {
        $this->user = User::create([
            'id' => 135,
            'uuid' => 'bad46547-afbc-412b-b421-ea8df216c774',
            'name' => 'hamid chakkouri',
            'email' => 'zcrroll@gmail.com',
            'google_id' => '106700729275441873960',
            'password' => '$2y$12$QiInPF4r9sJ9jBCKuEs3suwuybKo8/RGtaFK1fyF4YAvro4wWAOdi',
            'remember_token' => 'RQtltbw2YyOo82iZeppR0lRiw5aey3ir4krwU9hoNU7j30HDsliJIrDJBw4d',
            'email_verified_at' => Carbon::parse('2024-11-20 10:59:08'),
            'created_at' => Carbon::parse('2024-11-20 10:59:08'),
            'updated_at' => Carbon::parse('2024-12-20 16:04:50')
        ]);
    }
});

describe('Password Update', function() {
    // it('can update user password with valid data', function() {
    //     $action = new UpdateUserPassword();
    //     $newPassword = 'new-password123!';
        
    //     $action->update($this->user, [
    //         'current_password' => '$2y$12$QiInPF4r9sJ9jBCKuEs3suwuybKo8/RGtaFK1fyF4YAvro4wWAOdi',
    //         'password' => $newPassword,
    //         'password_confirmation' => $newPassword,
    //     ]);

    //     $this->assertTrue(Hash::check($newPassword, $this->user->fresh()->password));
    // });

    it('validates current password', function() {
        $action = new UpdateUserPassword();
        
        try {
            $action->update($this->user, [
                'current_password' => 'wrong-password',
                'password' => 'new-password123!',
                'password_confirmation' => 'new-password123!',
            ]);
            
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('current_password', $e->errors());
        }
    });

    it('requires password confirmation to match', function() {
        $action = new UpdateUserPassword();
        
        try {
            $action->update($this->user, [
                'current_password' => '$2y$12$QiInPF4r9sJ9jBCKuEs3suwuybKo8/RGtaFK1fyF4YAvro4wWAOdi',
                'password' => 'new-password123!',
                'password_confirmation' => 'different-password',
            ]);
            
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('password', $e->errors());
        }
    });
});

describe('Password Reset', function() {
    it('can reset user password', function() {
        $action = new ResetUserPassword();
        $newPassword = 'new-password123!';
        
        $action->reset($this->user, [
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $this->assertTrue(Hash::check($newPassword, $this->user->fresh()->password));
    });

    it('validates password confirmation for reset', function() {
        $action = new ResetUserPassword();
        
        try {
            $action->reset($this->user, [
                'password' => 'new-password123!',
                'password_confirmation' => 'different-password',
            ]);
            
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('password', $e->errors());
        }
    });

    it('enforces password complexity rules for reset', function() {
        $action = new ResetUserPassword();
        
        try {
            $action->reset($this->user, [
                'password' => 'weak',
                'password_confirmation' => 'weak',
            ]);
            
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('password', $e->errors());
        }
    });
}); 