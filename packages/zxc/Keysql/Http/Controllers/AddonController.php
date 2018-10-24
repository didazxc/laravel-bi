<?php
namespace Zxc\Keysql\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Zxc\Keysql\Models\KeySql;
use Auth;
use Route;
use Redirect;

class AddonController extends BaseController
{
    public function pivottable(Request $request){
        $sql_id=$request->input('sql_id',0);
        if($sql_id){
            $keysql=KeySql::find($sql_id);
            $res=$keysql->getTableData($request->input());
        }else{
            $res=[];
        }
        return view('keysql::unit.keysql.pivottable',compact('res'));
    }
    
    
}
