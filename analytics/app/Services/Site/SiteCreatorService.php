<?php

namespace App\Services\Site;

use App\Models\Site;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SiteCreatorService
{
    public function create($data, $user)
    {
        try {
            DB::beginTransaction();
            $site = new Site();
            $site->fqdn = $data['domain_name'];
            $site->timezone = $data['timezone'];
            $site->active = 1;
            $site->save();
            $user->sites()->attach($site->id, ['role' => 'admin']);
            DB::commit();
            return $site;
        } catch (QueryException $e) {
            DB::rollback();
            throw new Exception('An error occurred while saving the site.');
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }
}
