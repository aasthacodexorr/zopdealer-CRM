<?php

namespace App\Libraries\Google;

class Places
{
    public static function getSuggestions(String $search)
    {
        $payload = [];
        $payload['input'] = $search;
        $payload['includedRegionCodes'] = ['ca'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://places.googleapis.com/v1/places:autocomplete');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Goog-FieldMask: suggestions.placePrediction.place,suggestions.placePrediction.placeId,suggestions.placePrediction.text.text',
            'Content-Type: application/json',
            'X-Goog-Api-Key: ' . getenv('google.console.maps.key'),
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return [
                'errors' => curl_error($ch)
            ];
        }
        curl_close($ch);

        $response = json_decode($result, true);

        $places = [];
        if (!empty($response['suggestions'])) {
            foreach ($response['suggestions'] as $key => $predicted_place) {
                if (!empty($predicted_place['placePrediction'])) {
                    $predicted_place['placePrediction']['text'] = !empty($predicted_place['placePrediction']['text']) ?
                        $predicted_place['placePrediction']['text']['text'] : "";
                    array_push($places, $predicted_place['placePrediction']);
                }
            }
        }

        return ['success' => 'Results for ' . $search . ' ...', 'places' => $places];
    }

    public static function getLocation(String $place)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://places.googleapis.com/v1/places/' . $place);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Goog-FieldMask: addressComponents',
            'X-Goog-Api-Key: ' . getenv('google.console.maps.key'),
        ]);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return [
                'errors' => curl_error($ch)
            ];
        }
        curl_close($ch);

        $response = json_decode($result, true);

        $location = ['street_number' => '', 'street' => '',  'city' => '', 'province' => '', 'country' => '',  'postal_code' => ''];

        if (!empty($response['addressComponents'])) {
            $addressComponents = $response['addressComponents'];
            foreach ($addressComponents as $key => $component) {
                if (in_array('country', $component['types'])) {
                    $location['country'] = $component['shortText'];
                } else if (in_array('postal_code', $component['types'])) {
                    $location['postal_code'] = $component['shortText'];
                } else if (in_array('administrative_area_level_1', $component['types'])) {
                    $location['province'] = $component['shortText'];
                } else if (in_array('locality', $component['types'])) {
                    $location['city'] = $component['shortText'];
                } else if (in_array('route', $component['types'])) {
                    $location['street'] = $component['shortText'];
                } else if (in_array('street_number', $component['types'])) {
                    $location['street_number'] = $component['shortText'];
                }
            }
        }

        return array_merge(['success' => 'Location fetched successfully.'], ['place' => $location]);
    }
}
