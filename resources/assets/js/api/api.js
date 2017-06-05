import axios from 'axios';
let base = '';

export const requestLogin = params => { return axios.post(`${base}/login`, params).then(res => res.data); };
export const logout = params => { return axios.post(`${base}/logout`, params).then(res => res.data); };

export const getUserListPage = params => { return axios.get(`${base}/user/list`, { params: params }); };
export const removeUser = params => { return axios.post(`${base}/user/delete`, { params: params }); };
export const addUser = params => { return axios.post(`${base}/user/store`, { params: params }); };
export const editUser = params => { return axios.post(`${base}/user/edit`, { params: params }); };
export const batchRemoveUser = params => { return axios.post(`${base}/user/batchRemoveUser`, { params: params }); };

export const getRoleListPage = params => { return axios.get(`${base}/role`, { params: params }); };
export const removeRole = params => { return axios.delete(`${base}/role/`+params.id, {}); };
export const addRole = params => { return axios.post(`${base}/role`, { params: params }); };
export const editRole = params => { return axios.put(`${base}/role/`+params.id, { params: params }); };
export const batchRemoveRole = params => { return axios.post(`${base}/user/batchRemoveUser`, { params: params }); };
export const getTotalRoleList = params => { return axios.get(`${base}/role/create`, { params: params }); };
export const getRoleList = params => { return axios.get(`${base}/role/`+params.id, { params: params }); };
export const setRoleList = params => { return axios.post(`${base}/user/role/`+params.id, { params: params }); };


//查系统所有的权限
export const getPermissionListPage = params => { return axios.get(`${base}/permission/getAll`, { params: params }); };
//查XX的权限
export const getPermissionListOfRole = params => { return axios.get(`${base}/permission/role/`+params.id, {}); };
//查寻当前用户的权限
export const getPermissionList = params => { return axios.get(`${base}/permission/list`, { params: params }); };
export const setPermissionList = params => { return axios.post(`${base}/permission/update/`+params.id, { params: params }); };

