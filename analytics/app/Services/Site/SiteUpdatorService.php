<?php

namespace App\Services\Site;

use App\Models\Site;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SiteUpdatorService
{
    public function update($data)
    {
        try {
            DB::beginTransaction();
            $site = Site::find($data['site_id']);
            $site->fqdn = $data['domain_name'];
            $site->timezone = $data['timezone'];
            $site->active = 1;
            $site->save();
            DB::commit();
            return $site;
        } catch (QueryException $e) {
            DB::rollback();
            throw new Exception('An error occurred while updating the site.');
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }
}
