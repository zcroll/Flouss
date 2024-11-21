<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchetypeDiscovery extends Model
{
    use HasFactory;

    protected $table = 'archetype_discoveries';

    protected $fillable = [
        'slug',
        'type',
        'notification_text',
        'verbose_description',
        'verbose_description_fr',
        'image_url',
        'rationale',
        'rationale_fr',
        'short_descriptor',
        'short_descriptor_fr',
        'verbose_description_header',
        'verbose_description_header_fr',
        'scales_descriptor',
        'scales_descriptor_fr',
        'strengths_body',
        'strengths_body_fr',
        'weaknesses_body',
        'weaknesses_body_fr',
        'scales',
        'name'
        // Add any other fields you need
    ];
} 