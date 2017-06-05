/**
 * Created by liyuequn on 2017/5/15.
 */

import navigation from "./components/Navigation.vue";
import Login from "./components/Login.vue";
import NotFound from "./components/404.vue";

import User from "./components/rbac/User.vue";
import Permission from "./components/rbac/Permission.vue";
import Role from "./components/rbac/Role.vue";

var fk_permission ;
fun('permission')==true? fk_permission = false:fk_permission = true;

var fk_permission_user ;
fun('permission')==true? fk_permission_user = false:fk_permission_user = true;

var fk_permission_role ;
fun('permission')==true? fk_permission_role = false:fk_permission_role = true;

var fk_permission_per ;
fun('permission')==true? fk_permission_per = false:fk_permission_per = true;


function fun(funKey) {
    let res = JSON.parse(sessionStorage.getItem('permission'));
    if(res!=null){
        var i = res.length;
        while (i--) {
            if (res[i] === funKey) {
                return true;
            }
        }
    }

}






 let routes = [
     {
         path: '/login',
         component: Login,
         name: '',
         hidden: true
     },
     {
         path: '/404',
         component: NotFound,
         name: '',
         hidden: true
     },
     {
        path: '/',
        component: navigation,
        name: '权限管理',
        iconCls: 'el-icon-message',//图标样式class
         hidden:fk_permission,
        children: [
            { path: '/user', component: User, name: '用户' ,hidden:fk_permission_user},
            { path: '/role', component: Role, name: '角色' ,hidden:fk_permission_role},
            { path: '/fun', component: Permission, name: '权限',hidden:fk_permission_per },
        ]
     },
     
]

export default routes;


