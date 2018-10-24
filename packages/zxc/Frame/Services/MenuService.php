<?php

namespace Zxc\Frame\Services;

use Zxc\Frame\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

class MenuService
{
    /*
     * 返回路径对应的节点
     */
    static public function getPathNode($path)
    {
        $path = '/' . trim($path,'/');
        $thisNode = Menu::where('url', $path)->first();
        if (!$thisNode) {
            $subPaths = explode("/", $path);
            if (is_numeric(array_pop($subPaths))) {
                $newPath = implode("/", $subPaths);
                $thisNode = Menu::where("url", $newPath)->first();
            }
        }
        return $thisNode;
    }

    /*
     * 返回路径Collection
     */
    static public function getPaths($path){
        $thisNode=MenuService::getPathNode($path);
        if($thisNode){
            $paths=$thisNode->ancestors()->where('id','>','2')->get();
            $paths->push($thisNode);
        }else{
            $paths=new Collection();
        }
        return $paths;
    }

    /*
     * 从目录树中返回路径数组
     */
    static private function getPathsArrayByMenus($path,$menus){
        $resPath=[];
        foreach($menus as $item){
            if(count($item->children)>0){
                $resPath=MenuService::getPathsArrayByMenus($path,$item->children);
                if(count($resPath)>0){
                    array_unshift($resPath,$item);
                    break;
                }
            }else{
                if($item->url==$path){
                    $resPath[]=$item;
                    break;
                }
            }
        }
        return $resPath;
    }

    static public function getPathsByMenus($path,$menus){
        return new Collection(MenuService::getPathsArrayByMenus($path,$menus));
    }

    /*
     * 权限判断,针对menu判断权限
     * 所有父类节点必须都有权限，（两个根节点除外，因此根节点下第一层目录必须自身有权限）
     * return boolean
     */
    static private function canPermByPermissions($menu,$permissions){
        //非根路径第一层的叶子节点，不需要权限判断，默认为有权限
        if($menu->parent_id>2 && count($menu->children)==0){
            return true;
        }
        if(!in_array($menu->permission,$permissions)){
            return false;
        }
        return true;
    }

    static private function canPermByPaths($paths,$user){
        if($paths->isEmpty()){//不在目录列表里的路径，无权限
            return false;
        }
        foreach($paths->all() as $menu){
            //（除根路径第一层的叶子节点外）的所有叶子节点，不需要权限判断，默认为有权限
            if($menu->parent_id>2 && count($menu->children)==0){
                continue;
            }
            if(!$user->can($menu->permission)){
                return false;
            }
        }
        return true;
    }

    static public function canPerm($path,$user){
        $paths=MenuService::getPaths($path);
        return MenuService::canPermByPaths($paths,$user);
    }

    /*
     * 获取有权限的目录树
     */
    static public function getPermedMenus($user,$rootId){
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $isAdminUser=in_array('admin',$permissions);
        $menus=Menu::descendantsOf($rootId);
        if(!$isAdminUser){
            $menus=$menus->filter(function($item)use($permissions){
                return MenuService::canPermByPermissions($item,$permissions);
            });
        }
        return $menus->toTree($rootId);
    }

}
