<template>
    <section>
        <!--工具条-->
        <el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
            <el-form :inline="true" :model="filters">
                <el-form-item>
                    <el-input v-model="filters.name" placeholder="姓名"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" v-on:click="getUsers">查询</el-button>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="handleAdd">新增</el-button>
                </el-form-item>
            </el-form>
        </el-col>
        <!--列表页-->
        <el-table :data="users" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中" @selection-change="selsChange" style="width: 100%;">
            <el-table-column type="selection" width="55">
            </el-table-column>
            <el-table-column type="index" width="60">
            </el-table-column>
            <el-table-column prop="name" label="姓名" width="150" sortable>
            </el-table-column>
            <el-table-column prop="sex" label="性别" width="100" :formatter="formatSex" sortable>
            </el-table-column>
            <el-table-column prop="email" label="邮箱" width="200" sortable>
            </el-table-column>
            <el-table-column prop="created_at" label="创建时间" width="220" sortable>
            </el-table-column>
            <el-table-column prop="addr" label="角色" min-width="180" sortable>
            </el-table-column>
            <el-table-column label="操作" width="200">
                <template scope="scope">
                    <el-button size="small" @click="handleEdit(scope.$index, scope.row)"><i class="el-icon-edit"></i></el-button>
                    <el-button type="danger" size="small" @click="handleDel(scope.$index, scope.row)"><i class="el-icon-delete"></i></el-button>
                    <el-button type="small" size="small" @click="handleSet(scope.$index, scope.row)"><i class="el-icon-setting"></i></el-button>
                </template>
            </el-table-column>
        </el-table>
        <!--编辑界面-->
        <el-dialog title="编辑" v-model="editFormVisible" :close-on-click-modal="false">
            <el-form :model="editForm" label-width="80px" :rules="editFormRules" ref="editForm">
                <el-form-item label="姓名" prop="name">
                    <el-input v-model="editForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="性别" prop="sex">
                    <el-radio-group v-model="editForm.sex">
                        <el-radio class="radio" :label="'1'">男</el-radio>
                        <el-radio class="radio" :label="'2'">女</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="邮箱" prop="email">
                    <el-input v-model="editForm.email" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="手机号" prop="phone">
                    <el-input v-model="editForm.phone" auto-complete="off"></el-input>
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
                <el-form-item label="姓名" prop="name">
                    <el-input v-model="addForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="性别" prop="sex">
                    <el-radio-group v-model="addForm.sex">
                        <el-radio class="radio" :label="1">男</el-radio>
                        <el-radio class="radio" :label="2">女</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="邮箱" prop="email">
                    <el-input v-model="addForm.email" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="手机号" prop="phone">
                    <el-input v-model="addForm.phone" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="addFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>
            </div>
        </el-dialog>
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
        <!--设置角色-->
        <el-dialog title="设置" :visible.sync="dialogRoleVisible">
                <el-select
                        v-model="Roles"
                        multiple
                        filterable
                        remote
                        placeholder="请输入关键词"
                        :remote-method="remoteMethod"
                        :loading="roleLoading">
                    <el-option
                            v-for="item in options4"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="dialogRoleVisible = false">取消</el-button>
                <el-button type="primary" @click.native="roleSubmit" :loading="roleLoading">提交</el-button>
            </div>
        </el-dialog>

    </section>
</template>
<script>
    import { getUserListPage, removeUser ,addUser,editUser,batchRemoveUser,getTotalRoleList,getRoleList,setRoleList} from '../../api/api';

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
                users:[],
                listLoading: false,
                sels: [],//列表选中列

                editFormVisible: false,//编辑界面是否显示
                editLoading: false,
                editFormRules: {
                    name: [
                        { required: true, message: '请输入姓名', trigger: 'blur' }
                    ],
                    sex:[
                        { required: true, message:'不能为空',trigger:'blur' }
                    ],
                    email:[
                        { required: true, message:'不能为空',trigger:'blur' }
                    ],
                    phone:[
                        { required: true, message:'不能为空',trigger:'blur' }
                    ]
                },
                //编辑界面数据
                editForm: {
                    id: 0,
                    name: '',
                    sex: 1,
                    email: '',
                    phone: '',
                    addr: ''
                },
                addFormVisible: false,//新增界面是否显示
                addLoading: false,
                addFormRules: {
                    name: [
                        { required: true, message: '请输入姓名', trigger: 'blur' }
                    ],
                    sex:[
                        {required: true, message:'输入性别',trigger:'blur' }
                    ],
                    email:[
                        {required: true, message:'输入邮箱',trigger:'blur' }
                    ],
                    phone:[
                        {required: true, message:'输入手机号',trigger:'blur' }
                    ]
                },
                //新增界面数据
                addForm: {
                    name: '',
                    sex: 1,
                    email: '',
                    phone: '',
                    addr: ''
                },
                //角色参数
                userId:0,
                dialogRoleVisible:false,//设置角色的框是否显示
                Roles:[],//选中后的角色
                options4:[],
                roleLoading:false,
                states: [],
                list: [],
            }
        },
        methods:{
            roleSubmit: function () {
                this.$confirm('确认提交吗？', '提示', {}).then(() => {
                    this.roleLoading = true;
                    let para = {
                        id:this.userId,
                        value:this.Roles,
                    }
                    setRoleList(para).then((res)=>{
                        this.roleLoading = false;
                        this.$message({
                            message: '保存成功',
                            type: 'success'
                        });
                        this.dialogRoleVisible = false;
                    });
                });
            },
            remoteMethod(query) {
                if (query !== '') {
                    this.roleLoading = true;
                    setTimeout(() => {
                        this.roleLoading = false;
                        this.options4 = this.list.filter(item => {
                            return item.label.toLowerCase()
                                    .indexOf(query.toLowerCase()) > -1;
                        });
                    }, 200);
                } else {
                    this.options4 = [];
                }
            },
            handleSet(index,row){
                this.userId = row.id;
                this.getTotalRoles();
                this.getRoles();
                this.dialogRoleVisible = true;
            },
            //获取当前用户的所有角色
            getRoles() {
                let para = {
                    id:this.userId
                };
                getRoleList(para).then((res) => {
                    let arr = [];
                    for ( var i in res.data ){
                        arr.push(res.data[i])
                    }
                    this.Roles = arr;
                });
            },
            //获取系统所有的角色
            getTotalRoles() {
                let para = {
                    name: this.filters.name
                };
                this.listLoading = true;
                getTotalRoleList(para).then((res) => {
                    let arr = [];
                    for ( var i in res.data ){
                        arr.push(res.data[i])
                    }
                    this.states = arr;
                    this.listLoading = false;
                    this.list = this.states.map(item => {
                        return { value: item, label: item };
                    });
                });
            },
            //性别显示转换
            formatSex: function (row, column) {
                return row.sex == 1 ? '男' : row.sex == 2 ? '女' : '未知';
            },
            //页面跳转后
            handleCurrentChange(val) {
                this.page = val;
                this.getUsers();
            },
            //更改每页显示数据
            handleSizeChange(val){
                this.pageSize =val;
                this.getUsers();
            },
            //获取用户列表
            getUsers() {
                let para = {
                    page: this.page,
                    pageSize: this.pageSize,
                    name: this.filters.name
                };
                this.listLoading = true;
                getUserListPage(para).then((res) => {
                    this.total = res.data.total;
                    this.users = res.data.data;
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
                    removeUser(para).then((res) => {
                        this.listLoading = false;
                        //NProgress.done();
                        this.$message({
                            message: '删除成功',
                            type: 'success'
                        });
                        this.getUsers();
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
                    sex: 0,
                    email: '',
                    phone: '',
                    addr: ''
                };
            },
            //编辑
            editSubmit: function () {
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.editLoading = true;
                            //NProgress.start();
                            let para = Object.assign({}, this.editForm);
                            //para.birth = (!para.birth || para.birth == '') ? '' : util.formatDate.format(new Date(para.birth), 'yyyy-MM-dd');
                            editUser(para).then((res) => {
                                this.editLoading = false;
                                //NProgress.done();
                                this.$message({
                                    message: '提交成功',
                                    type: 'success'
                                });
                                this.$refs['editForm'].resetFields();
                                this.editFormVisible = false;
                                this.getUsers();
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
                            addUser(para).then((res) => {
                                this.addLoading = false;
                                //NProgress.done();
                                this.$message({
                                    message: '提交成功',
                                    type: 'success'
                                });
                                this.$refs['addForm'].resetFields();
                                this.addFormVisible = false;
                                this.getUsers();
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
                    batchRemoveUser(para).then((res) => {
                        this.listLoading = false;
                        //NProgress.done();
                        this.$message({
                            message: '删除成功',
                            type: 'success'
                        });
                        this.getUsers();
                    });
                }).catch(() => {

                });
            }
        },
        mounted() {
            this.getUsers();
        }
    }
</script>