<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | FooTable</title>

    <link href="/backend/css/bootstrap.min.css" rel="stylesheet">
    <link href="/backend/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- FooTable -->
    <link href="/backend/css/plugins/footable/footable.core.css" rel="stylesheet">

    <link href="/backend/css/style.css" rel="stylesheet">

</head>

<body>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>FooTable with row toggler, sorting and pagination</h5>

                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <table class="footable table table-stripped toggle-arrow-tiny">
                            <thead>
                                <tr>

                                    <th data-toggle="true">Project</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th data-hide="all">Company</th>
                                    <th data-hide="all">Completed</th>
                                    <th data-hide="all">Task</th>
                                    <th data-hide="all">Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Project - This is example of project</td>
                                    <td>Patrick Smith</td>
                                    <td>0800 051213</td>
                                    <td>Inceptos Hymenaeos Ltd</td>
                                    <td><span class="pie">0.52/1.561</span></td>
                                    <td>20%</td>
                                    <td>Jul 14, 2013</td>
                                    <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Project
                                        <small>This is example of project</small>
                                    </td>
                                    <td>Patrick Smith</td>
                                    <td>0800 051213</td>
                                    <td>Inceptos Hymenaeos Ltd</td>
                                    <td><span class="pie">0.52/1.561</span></td>
                                    <td>20%</td>
                                    <td>Jul 14, 2013</td>
                                    <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
            <!-- Mainly scripts -->
            <script src="/backend/js/jquery-3.1.1.min.js"></script>
            <script src="/backend/js/bootstrap.min.js"></script>
    
            <!-- FooTable -->
            <script src="/backend/js/plugins/footable/footable.all.min.js"></script>
    
            <!-- Page-Level Scripts -->
            <script>
                $(document).ready(function() {
    
                    $('.footable').footable();
                    $('.footable2').footable();
    
                });
            </script>
</body>

</html>
