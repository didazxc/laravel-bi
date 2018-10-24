<?php
namespace Zxc\Api\Http\Controllers;

use Illuminate\Http\Request;
use Zxc\Api\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Zxc\Api\Models\ApiPermission;

class ApiController extends Controller
{

    use ApiResponse;

    // 其他通用的Api帮助函数

    public $action='';

    public function __construct(Request $request)
    {
        $this->action=$request->route()->action['controller'];
    }

    public function verifyPermission()
    {
        $perms=ApiPermission::getPerms($this->action);
        return $perms->isNotEmpty();
    }

    public function getApiJsonCollect()
    {
        return ApiPermission::getJsons($this->action);
    }

}