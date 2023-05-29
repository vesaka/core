<?php

namespace Vesaka\Core\Models;

use Vesaka\Core\Traits\Models\FilepondFeaturedImageTrait;
use Illuminate\Support\Str;
/**
 * Description of Website
 *
 * @author vesak
 */
class Website extends Model {
    use FilepondFeaturedImageTrait;
    
    public function snap($url)
    {
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
            $curl_init = curl_init("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url={$url}&screenshot=true");
            curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl_init);
            curl_close($curl_init);

            $googlepsdata = json_decode($response, true);
            //screenshot data
            $snap = Str::of($googlepsdata['screenshot']['data'])
                    ->replace('_', '-')
                    ->replace('/', '+');

            return $snap;
        } else {
            return false;
        }
    }
}
