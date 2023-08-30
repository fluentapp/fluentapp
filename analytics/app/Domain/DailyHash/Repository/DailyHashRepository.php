<?php

namespace App\Domain\DailyHash\Repository;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class DailyHashRepository extends Model {

    use HasFactory;

    protected $table = 'daily_hash';
    protected $fillable = ['hash'];

    public function getLatest(): string {
        return DailyHashRepository::latest()->first()->hash;
    }

    /**
     * 
     * Creates a new random hash and
     * @return string 
     */
    public function rotate(): string {

        $randomHash = $string = bin2hex(openssl_random_pseudo_bytes(12));
        $this->fill(['hash' => $randomHash]);
        $this->save();

        return $this->hash;
    }
}
