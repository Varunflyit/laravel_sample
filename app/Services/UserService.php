<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Ecommify\Core\Models\AuthAssignment;
use Ecommify\Core\Models\UserCompanyMap;

class UserService
{

    public function __construct()
    {

    }

    /**
     * check if user can access the instance
     *
     * @param array $options
     * @return array
     */
    public static function hasInstanceAccess($userId, $integrationInstanceData)
    {
        $authAssignment = AuthAssignment::where('user_id', (string)$userId)->first();
        if(!$authAssignment)
            return [
                'success' => false,
                'message' => trans('app.forbidden')
            ];

        // block user-type = user
        if($authAssignment->item_name == 'user')
            return [
                'success' => false,
                'message' => trans('app.forbidden')
            ];
        // check if user company has access of the integration
        else
        if($authAssignment->item_name == 'masterUser') {
            $userCompany = UserCompanyMap::where('user_id', (string)$userId)->pluck('company_id')->toArray();
            if(!$userCompany)
                return [
                    'success' => false,
                    'message' => trans('app.forbidden')
                ];

            if(!in_array($integrationInstanceData->company_id, $userCompany))
            {
                return [
                    'success' => false,
                    'message' => trans('app.forbidden')
                ];
            }
        }
        
        return [
            'success' => true
        ];
    }

}
