<template>
    <div class="container">
        <div class="row">
            <div class="col-md-11 section mx-auto">
                    <h3>TO DO LIST</h3>
                <div class="row">
                    <div class="col-md-2">
                        <h6>Show Category:</h6>
                    </div>
                    <div class="col-md-9 text-left">
                        <ul class="list-inline">
                            <li v-for="category in categories" @click.prevent="categoryDataView(category.id,category.name)" class="list-inline-item"><a href="#">{{ category.name}} |</a></li>
                            <li v-if="categories.length > 0"  @click.prevent="allTodoList()" class="list-inline-item"><a href="#">All</a></li>
                        </ul>
                    </div>
                </div>
                <span v-if="categories.length > 0"><strong>( {{ selectedCategoryName != ''? selectedCategoryName: 'All' }} ) </strong></span> <br>
                <div v-if="todolists.length > 0" class="row" v-for="(todo,index) in todolists" :key="index">
                        <div class="col-md-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" :id="`defaultCheck1${index}`">
                                <label class="form-check-label" :for="`defaultCheck1${index}`">
                                    <del v-if="delTag">{{ todo.title}}</del>
                                    <span>{{ todo.title}}</span>
                                </label>
                            </div>
                        </div>
                    <div class="col-md-4 text-right">
                        <label class="form-check-label" for="">
                            <i @click="toDoDelete(todo.id)" class="fa-solid fa-xmark cursor-pointer"></i>
                        </label>
                    </div>


                </div>
                <span v-else class="text-danger"> Sorry Todo List Empty !!</span>
                <hr>
                <section>
                    <form @submit.prevent="addTodoList">
                        <div class="row">
                            <div class="col-md-12">
                                <form>
                                    <div class="form-group">
                                        <label for="add_to_do">ADD TO DO</label>
                                        <input type="text" v-model="todolist.title" class="form-control" id="add_to_do">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select class="form-control" v-model="todolist.category_id" id="category">
                                        <option value="">Choose Category</option>
                                        <option v-for="category in categories" :value="category.id">{{ category.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">.</label>
                                    <button type="submit" class="form-control btn btn-dark mb-2">Add TODO</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>

                <hr class="hrrow">

                <section>
                    <form @submit.prevent="addCategory" class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_to_do">CATEGORIES</label>
                                <input type="text" v-model="category.name" class="form-control" id="add_to_do">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">.</label>
                                <button type="submit" class="form-control btn btn-dark mb-2">Add Category</button>
                            </div>
                        </div>
                    </form>

                </section>

                <div class="row">
                    <div class="col-md-12">
                        <h6  v-for="category in categories"> <i @click="categoryDelete(category.id)" class="fa-solid fa-xmark text-danger cursor-pointer"></i> {{ category.name }}</h6>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
import "@fortawesome/fontawesome-free/css/all.css";
export default {
    data(){
        return {
            category:{
                name:''
            },
            selectedCategoryName : '',
            todolist:{
                category_id:'',
                title:''
            },
            categories:[],
            todolists:[],

        }
    }
    ,
    methods:{
        //category List view
        categoryLists(){
            axios.get('/api/categories')
                .then(response => {
                    this.categories = response.data.data
                });
        },
        //category create
        addCategory(){

                if(this.category.name == ''){
                    Toast.fire({
                        icon: 'error',
                        title: 'Category Name Field Required!!'
                    })
                    return false;
                }
                axios.post('/api/categories/',this.category).then(response=>{
                    this.category.name = ''
                    this.categoryLists()
                    Toast.fire({
                        icon: 'success',
                        title: 'Category Added successfully Done.'
                    })
                }).catch(error => {
                    this.category.name = ''
                        Toast.fire({
                            icon: 'error',
                            title: error.response.data.message.name[0]
                        })
                    });


        },
        //category delete
        categoryDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/categories/${id}`)
                        .then(response => {
                            let i = this.categories.map(data => data.id).indexOf(id);
                            this.categories.splice(i, 1)
                            this.categoryLists()
                            this.toDoLists()
                            Toast.fire({
                                icon: 'success',
                                title: 'Data Delete Successfully Done.'
                            })

                        });

                }
            })

        },

        //todo list data
        toDoLists(){
            axios.get('/api/todo-lists')
                .then(response => {
                    this.todolists = response.data.data
                });
        },
        //todo list create
        addTodoList(){
            if(this.todolist.title == '' || this.todolist.category_id == '' ){
                Toast.fire({
                    icon: 'error',
                    title: 'All Field are Required!!'
                })
                return false;
            }
            axios.post('/api/todo-lists/',this.todolist).then(response=>{
                this.todolist.title = ''
                this.todolist.category_id = ''
                this.selectedCategoryName = 'All'
                this.toDoLists()
                Toast.fire({
                    icon: 'success',
                    title: 'Todo List added successfully Done.'
                })
            }).catch(error => {
                this.todolist.title = ''
                this.todolist.category_id = ''
                Toast.fire({
                    icon: 'error',
                    title: error.response.data.message.title[0]
                })
            });
        },

        toDoDelete(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/todo-lists/${id}`)
                        .then(response => {
                            let i = this.todolists.map(data => data.id).indexOf(id);
                            this.todolists.splice(i, 1)
                            this.toDoLists()
                            Toast.fire({
                                icon: 'success',
                                title: 'Data Delete Successfully Done.'
                            })

                        });
                }
            })
        },

        categoryDataView(id,catname){
            axios.get(`/api/todo-lists/${id}`)
                .then(response => {
                    this.todolists = response.data.data
                    this.selectedCategoryName = catname
                });
        },

        allTodoList(){
            this.toDoLists()
            this.selectedCategoryName = 'All'
        }
    },
    created() {
        this.categoryLists()
        this.toDoLists()
    },


}
</script>
<style scoped>
    .container {
        background: darkblue;
        padding: 5px;
    }
    .section{
        margin: 5px;
        background: white;
        padding: 30px;
        border-radius: 5px;
    }
    .hrrow{
        width:100%;
        height: 1px;
        background: gray;
    }
    .cursor-pointer{
        cursor: pointer;
    }
</style>
