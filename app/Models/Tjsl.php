<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tjsl extends Model
{
    use HasFactory;

    protected $table = 'tb_tjsl';

    protected $fillable = [
        'nama_program',
        'deskripsi',
        'unit_id',
        'lokasi_program',
        'latitude',
        'longitude',
        'pilar_id',
        'program_unggulan_id',
        'sub_pilar',
        'tanggal_mulai',
        'tanggal_akhir',
        'penerima_dampak',
        'tpb',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'unit_id' => 'integer',
        'status' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_akhir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function pilar(): BelongsTo
    {
        return $this->belongsTo(Pilar::class, 'pilar_id');
    }

    public function programUnggulan(): BelongsTo
    {
        return $this->belongsTo(ProgramUnggulan::class, 'program_unggulan_id');
    }
    // Relasi dg tb_biaya_tjsl (one-to-many)
    public function biayaTjsl(): HasMany
    {
        return $this->hasMany(BiayaTjsl::class, 'tjsl_id');
    }

    public function pubTjsl(): HasMany
    {
        return $this->hasMany(PubTjsl::class, 'tjsl_id');
    }

    public function docTjsl(): HasMany
    {
        return $this->hasMany(DocTjsl::class, 'tjsl_id');
    }

    public function feedbackTjsl(): HasMany
    {
        return $this->hasMany(FeedbackTjsl::class, 'tjsl_id');
    }

    /**
     * Get sub pilar image path
     */
    // public function getSubPilarImageAttribute()
    // {
    //     $subPilarImage = strtolower(str_replace([' ', '_'], '-', $this->sub_pilar));
    //     $imagePath = 'img/sub_pilar/' . $subPilarImage . '.png';
    //     $fullImagePath = public_path($imagePath);

    //     return file_exists($fullImagePath) ? asset($imagePath) : null;
    // }

    // /**
    //  * Check if sub pilar has image
    //  */
    // public function hasSubPilarImage()
    // {
    //     return !is_null($this->sub_pilar_image);
    // }

    /**
     * Get sub pilar image paths as array
     */
    public function getSubPilarImagesAttribute()
    {
        if (empty($this->sub_pilar)) {
            return [];
        }

        // Handle if sub_pilar is stored as JSON string or comma-separated string
        $subPilarNumbers = [];

        if (is_string($this->sub_pilar)) {
            // Try to decode as JSON first
            $decoded = json_decode($this->sub_pilar, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $subPilarNumbers = $decoded;
            } else {
                // If not JSON, try comma-separated values
                $subPilarNumbers = array_map('trim', explode(',', $this->sub_pilar));
            }
        } elseif (is_array($this->sub_pilar)) {
            $subPilarNumbers = $this->sub_pilar;
        }

        $images = [];
        foreach ($subPilarNumbers as $number) {
            $imagePath = 'img/sub_pilar/' . $number . '.png';
            $fullImagePath = public_path($imagePath);

            if (file_exists($fullImagePath)) {
                $images[] = [
                    'path' => asset($imagePath),
                    'number' => $number,
                    'alt' => 'Sub Pilar ' . $number
                ];
            }
        }

        // Sort images by number in ascending order
        usort($images, function($a, $b) {
            return (int)$a['number'] <=> (int)$b['number'];
        });

        return $images;
    }

    /**
     * Check if sub pilar has any images
     */
    public function hasSubPilarImages()
    {
        return count($this->sub_pilar_images) > 0;
    }

    public function getProgramUnggulanImageAttribute()
    {
        $id = $this->program_unggulan_id;
        if (!$id) {
            return null;
        }

        $imagePath = 'img/program_unggulan/' . $id . '.png';
        $fullImagePath = public_path($imagePath);

        return file_exists($fullImagePath) ? asset($imagePath) : null;
    }

    public function hasProgramUnggulanImage()
    {
        return !is_null($this->program_unggulan_image);
    }
}
