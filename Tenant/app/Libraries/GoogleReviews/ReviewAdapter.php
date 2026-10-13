<?php

namespace Tenant\app\Libraries\GoogleReviews;

use Tenant\app\Libraries\SocialConnect\FacebookOps;
use Tenant\app\Libraries\SocialConnect\InstagramOps;

class ReviewAdapter
{

    //@todo This is not in use , to be removed in future
    public static function postReviewToSocial(string $source, string $text, string $image)
    {   
        $response = [];
        switch ($source) {
            case 'facebook':
                $fbops = new FacebookOps();
                $response = $fbops->postToDashboard($text ,$image);
                break;
            case 'instagram':
                $fbops = new InstagramOps();
                $response = $fbops->postToDashboard($text ,$image);
                break;
            default:
                $response['error'] = "Invalid Source Given.";
        }

        return $response;
        
    } 
}