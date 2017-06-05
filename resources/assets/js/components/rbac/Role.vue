<template>
    <section>
        <el-row>
            <!--工具条-->
            <el-form :inline="true" :model="filters">
                <el-form-item>
                    <el-input v-model="filters.name" placeholder="角色"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button v-if="this.fun('purchaseContractAdd')" type="primary" v-on:click="getRoles">查询</el-button>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="handleAdd">新增</el-button>
                </el-form-item>
            </el-form>
        </el-row>
        <el-row>
            <el-col :span="15">

                <!--列表页-->
                <el-table :data="Roles" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中" @selection-change="selsChange" style="width: 100%;">
                    <el-table-column type="selection" width="55">
                    </el-table-column>
                    <el-table-column type="index" width="60">
                    </el-table-column>
                    <el-table-column prop="name" label="角色" min-width="80" sortable>
                    </el-table-column>
                    <el-table-column prop="description" label="说明" min-width="100" sortable>
                    </el-table-column>
                    <el-table-column label="操作" width="200">
                        <template scope="scope">
                            <el-button size="small" @click="handleEdit(scope.$index, scope.row)"><i class="el-icon-edit"></i></el-button>
                            <el-button type="danger" size="small" @click="handleDel(scope.$index, scope.row)"><i class="el-icon-delete"></i></el-button>
                            <el-button type="small" size="small" @click="handleSet(scope.$index, scope.row)"><i class="el-icon-setting"></i></el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <!-- 分页-->
                <el-col :span="24" class="toolbar">
                    <el-button type="danger" @click="batchRemove" :disabled="this.sels.length===0">批量删除</el-button>
                    <el-pagination
                            @size-change="handleSizeChange"
                            @current-change="handleCurrentChange"
                            :current-page="currentPage"
                            :page-sizes="pageSizes"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total=total
                            style="float:right">
                    </el-pagination>
                </el-col>
            </el-col>
            <el-col :span="8" style="margin-left: 5px;">
                    <div style="height: 40px;background:#EEF1F6;text-align:center;line-height: 40px;font-size: 14px;">
                        <b>{{roleName}}权限</b>
                    </div>
                <el-tree
                        :data="data2"
                        show-checkbox
                        node-key="id"
                        ref="tree"
                        :default-expanded-keys="[]"
                        :default-checked-keys="checked"
                        :props="defaultProps">

                </el-tree>
                <el-button type="primary" @click="getCheckedKeys">保存</el-button>
            </el-col>
        </el-row>
            <!--编辑界面-->
        <el-dialog title="编辑" v-model="editFormVisible" :close-on-click-modal="false">
            <el-form :model="editForm" label-width="80px" :rules="editFormRules" ref="editForm">
                <el-form-item label="角色" prop="name">
                    <el-input v-model="editForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="说明" prop="description">
                    <el-input v-model="editForm.description" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="editSubmit" :loading="editLoading">提交</el-button>
            </div>
        </el-dialog>
        <!--新增界面-->
        <el-dialog title="新增" v-model="addFormVisible" :close-on-click-modal="false">
            <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
                <el-form-item label="角色" prop="name">
                    <el-input v-model="addForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="说明" prop="description">
                    <el-input v-model="addForm.description" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="addFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>
            </div>
        </el-dialog>



    </section>
</template>
<script>
    import {
        getRoleListPage,
        removeRole ,
        addRole,
        editRole,
        batchRemoveRole,
        getPermissionListPage,
        getPermissionListOfRole,
        setPermissionList
    } from '../../api/api';
    export default{
        data(){
            return {
                filters:{
                    name:'',
                },
                //分页类数据
                total:0,
                currentPage:0,
                pageSize:10,
                pageSizes:[10, 20, 30, 40, 50, 100],
                Roles:[],
                listLoading: false,
                sels: [],//列表选中列

                editFormVisible: false,//编辑界面是否显示
                editLoading: false,
                editFormRules: {
                    name: [
                        { required: true, message: '请输入角色', trigger: 'blur' }
                    ],
                },
                //编辑界面数据
                editForm: {
                    id: 0,
                    name: '',
                },
                addFormVisible: false,//新增界面是否显示
                addLoading: false,
                addFormRules: {
                    name: [
                        { required: true, message: '请输入角色', trigger: 'blur' }
                    ],

                },
                //新增界面数据
                addForm: {
                    name: '',
                },
                //权限
                data2:[],
                defaultProps: {
                    children: 'children',
                    label: 'label'
                },
                roleId:0,
                roleName:'',
                //被选中的权限
                checked:[],
            }
        },
        methods:{
            //获取当前被选中的所有节点，保存
            getCheckedKeys() {
                this.$confirm('确认提交吗？', '提示', {}).then(() => {
                    let para = {
                        id:this.roleId,
                        permissions:this.$refs.tree.getCheckedKeys(),
                    }
                    setPermissionList(para).then((res) => {
                        this.$message({
                            message: '提交成功',
                            type: 'success'
                        });
                    });
                });


            },
            //权限设置
            handleSet(index,row){
                this.roleName = row.name+'_';
                this.roleId = row.id;
                let para = {
                    id:row.id

                };
                getPermissionListOfRole(para).then((res) => {
                    this.checked = res.data;
                });
                let para2 = {
                };
                getPermissionListPage(para2).then((res) => {
                    this.data2 = res.data;
                });
            },
            //页面跳转后
            handleCurrentChange(val) {
                this.page = val;
                this.getRoles();
            },
            //更改每页显示数据
            handleSizeChange(val){
                this.pageSize =val;
                this.getRoles();
            },
            //获取用户列表
            getRoles() {
                let para = {
                    page: this.page,
                    pageSize: this.pageSize,
                    name: this.filters.name
                };
                this.listLoading = true;
                getRoleListPage(para).then((res) => {
                    this.total = res.data.total;
                    this.Roles = res.data.data;
                    this.listLoading = false;
                });
            },
            //删除
            handleDel: function (index, row) {
                this.$confirm('确认删除该记录吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    //NProgress.start();
                    let para = { id: row.id };
                    removeRole(para).then((res) => {
                        this.listLoading = false;
                        //NProgress.done();
                        this.$message({
                            message: '删除成功',
                            type: 'success'
                        });
                        this.getRoles();
                    });
                }).catch(() => {

                });
            },
            //显示编辑界面
            handleEdit: function (index, row) {
                this.editFormVisible = true;
                this.editForm = Object.assign({}, row);
            },
            //显示新增界面
            handleAdd: function () {
                this.addFormVisible = true;
                this.addForm = {
                    name: '',
                    description: '',
                };
            },
            //编辑
            editSubmit: function () {
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.editLoading = true;
                            let para = Object.assign({}, this.editForm);
                            editRole(para).then((res) => {
                                this.editLoading = false;
                                this.$message({
                                    message: '提交成功',
                                    type: 'success'
                                });
                                this.$refs['editForm'].resetFields();
                                this.editFormVisible = false;
                                this.getRoles();
                            });
                        });
                    }
                });
            },
            //新增
            addSubmit: function () {
                this.$refs.addForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.addLoading = true;
                            //NProgress.start();
                            let para = Object.assign({}, this.addForm);
                            //para.birth = (!para.birth || para.birth == '') ? '' : util.formatDate.format(new Date(para.birth), 'yyyy-MM-dd');
                            addRole(para).then((res) => {
                                this.addLoading = false;
                                //NProgress.done();
                                this.$message({
                                    message: '提交成功',
                                    type: 'success'
                                });
                                this.$refs['addForm'].resetFields();
                                this.addFormVisible = false;
                                this.getRoles();
                            });
                        });
                    }
                });
            },
            selsChange: function (sels) {
                this.sels = sels;
            },
            //批量删除
            batchRemove: function () {
                var ids = this.sels.map(item => item.id).toString();
                this.$confirm('确认删除选中记录吗？', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    //NProgress.start();
                    let para = { ids: ids };
                    batchRemoveRole(para).then((res) => {
                        this.listLoading = false;
                        //NProgress.done();
                        this.$message({
                            message: '删除成功',
                            type: 'success'
                        });
                        this.getRoles();
                    });
                }).catch(() => {

                });
            }
        },
        mounted() {
            this.getRoles();

        }
    }
</script>