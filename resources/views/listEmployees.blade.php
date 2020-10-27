<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="/js/vue.min.js"></script>
    <script src="/js/axios.min.js"></script>
    <style type="text/css">
        body {
            margin-top: 20px;
            color: #1a202c;
            text-align: left;
            background-color: #e2e8f0;
        }

        .main-body {
            padding: 15px;
        }

        .card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, .125);
            border-radius: .25rem;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 1rem;
        }

        .gutters-sm {
            margin-right: -8px;
            margin-left: -8px;
        }

        .gutters-sm > .col, .gutters-sm > [class*=col-] {
            padding-right: 8px;
            padding-left: 8px;
        }

        .mb-3, .my-3 {
            margin-bottom: 1rem !important;
        }

        .bg-gray-300 {
            background-color: #e2e8f0;
        }

        .h-100 {
            height: 100% !important;
        }

        .shadow-none {
            box-shadow: none !important;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .btn-light {
            color: #1a202c;
            background-color: #fff;
            border-color: #cbd5e0;
        }

        .ml-2, .mx-2 {
            margin-left: .5rem !important;
        }

        .card-footer:last-child {
            border-radius: 0 0 .25rem .25rem;
        }

        .card-footer, .card-header {
            display: flex;
            align-items: center;
        }

        .card-footer {
            padding: .5rem 1rem;
            background-color: #fff;
            border-top: 0 solid rgba(0, 0, 0, .125);
        }


    </style>
</head>
<body>
<div class="container" id="employees">
    <div class="main-body">
        <div class="row" style="padding: 20px">
            <input v-model="search_parameter" class="form-control col-lg-10"
                   type="text"
                   placeholder="Search"
                   aria-label="Search"
                style="margin-right: 10px"
            >
            <button type="button" class="btn btn-light" @click="searchEmployee()">Search</button>
            <button v-if="allEmployees.length === 1"
                    type="button"
                    class="btn btn-light"
                    @click="window.location='/listEmployees'"
                    style="margin-left: 10px"
            >
                Back</button>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 gutters-sm">
            <div v-for="employee in allEmployees">
                <div class="col mb-3">
                    <div class="card">
                        <img src="https://via.placeholder.com/340x120/87CEFA/000000" alt="Cover" class="card-img-top">
                        <div class="card-body text-center">
                            <img :src="(employee.avatar === null) ? 'https://bootdey.com/img/Content/avatar/avatar1.png' : employee.avatar"
                                 style="width:100px;margin-top:-65px"
                                 class="img-fluid img-thumbnail rounded-circle border-0 mb-3">
                            <h5 class="card-title">@{{ employee.name }}</h5>
                            <p class="text-secondary mb-1">@{{ employee.title }}</p>
                            <p class="text-muted font-size-sm"
                               style="height: 70px; overflow: hidden"
                            >@{{ employee.bio !== null ? employee.bio : '' }}</p>
                        </div>
                        <div class="card-footer" style="border: black solid 1px">
                            <p class="text-secondary mb-1"><b>Company: @{{ employee.company }}</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    new Vue({
        el: '#employees',
        data: () => ({
            allEmployees: {!! $all_employees !!},
            search_parameter: null
        }),
        methods: {
            searchEmployee() {
                if (this.search_parameter === null) {
                    alert('You must enter an UUID/Name in order to search');
                }
                axios.post('/searchEmployee', {
                    'search_parameter': this.search_parameter
                }).then((response) => {
                    console.log(response);
                    this.allEmployees = [JSON.parse(response.data.employee)]
                });
            }
        },
    });
</script>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
