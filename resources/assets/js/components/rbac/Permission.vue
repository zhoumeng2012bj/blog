<template>
    <el-tree
        :data="data2"
        show-checkbox
        node-key="id"
        :default-expanded-keys="[2, 3]"
        :default-checked-keys="[5]"
        :props="defaultProps">
    </el-tree>
</template>

<script>
    import { getPermissionListPage} from '../../api/api';

    export default {
        data() {
            return {
                data2:[],
                defaultProps: {
                    children: 'children',
                    label: 'label'
                }
            };
        },
        methods:{
            getPermission() {
                let para = {
                };
                getPermissionListPage(para).then((res) => {
                    this.data2 = res.data;
                });
            },
        },
        mounted() {
            this.getPermission();
        }
    };
</script>