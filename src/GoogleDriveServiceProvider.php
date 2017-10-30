<?php

namespace ITHands\Packages\Filesystem;


use Illuminate\Support\Facades\Storage;

class GoogleDriveServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('google', function ($app, $config) {
            $client = new \Google_Client();

            //if set to use service account
            if(!empty($config['use_service_account'])){
                //setting file location of service account json file
                $client->setAuthConfig($config['service_account_file']);
                $client->addScope(['https://www.googleapis.com/auth/drive']);
            }else{
                $client->setClientId($config['client_id']);
                $client->setClientSecret($config['client_secret']);
                $client->refreshToken($config['refresh_token']);
                $client->setDeveloperKey($config['api_key']);
            }

            $service = new \Google_Service_Drive($client);
            $adapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, $config['folder_id']);
            return new \League\Flysystem\Filesystem($adapter);
        });
    }
}