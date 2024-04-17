<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Deal')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Deal Report')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip" title="<?php echo e(__('Download')); ?>"
           data-original-title="<?php echo e(__('Download')); ?>">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row" id="printableArea">
        <div class="col-sm-12">
            <input type="hidden" value="<?php echo e(__('Deal Report')); ?>" id="filename">

            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#general-report" class="list-group-item list-group-item-action border-0"><?php echo e(__('General Report')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#staff-report" class="list-group-item list-group-item-action border-0"><?php echo e(__('Staff Report')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#pipeline-report" class="list-group-item list-group-item-action border-0"><?php echo e(__('Pipelines Report')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div id="general-report">
                        <div class="card">
                            <div class="card-header">
                                <h5><?php echo e(__('This Week Deals Conversions')); ?></h5>
                            </div>
                            <div class="card-body pt-0 ">
                                <div id="deals-this-week"
                                     data-color="primary" data-height="280">
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5><?php echo e(__('Sources Conversion')); ?></h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="deals-sources-report" id="deals-sources-report"
                                     data-color="primary" data-height="280">
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-9">
                                        <h5><?php echo e(__('Monthly')); ?></h5>
                                    </div>
                                    <div class="col-3 float-right">
                                        <select name="month" class="form-control selectpicker" id="selectmonth" data-none-selected-text="Nothing selected" >
                                            <option value=""><?php echo e(__('Select Month')); ?></option>
                                            <option value="1"><?php echo e(__('January')); ?></option>
                                            <option value="2"><?php echo e(__('February')); ?></option>
                                            <option value="3"><?php echo e(__('March')); ?></option>
                                            <option value="4"><?php echo e(__('April')); ?></option>
                                            <option value="5"><?php echo e(__('May')); ?></option>
                                            <option value="6"><?php echo e(__('June')); ?></option>
                                            <option value="7"><?php echo e(__('July')); ?></option>
                                            <option value="8"><?php echo e(__('August')); ?></option>
                                            <option value="9"><?php echo e(__('September')); ?></option>
                                            <option value="10"><?php echo e(__('October')); ?></option>
                                            <option value="11"><?php echo e(__('November')); ?></option>
                                            <option value="12"><?php echo e(__('December')); ?></option>
                                        </select>

                                    </div>
                                </div>
                            </div>

                            <div class="card-body">

                                <div class="mt-3">
                                    <div id="deals-monthly" data-color="primary" data-height="280"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="staff-report" class="card">
                        <div class="card-header">
                            <h5><?php echo e(__('Staff Report')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php echo e(Form::label('From Date', __('From Date'),['class'=>'col-form-label'])); ?>

                                    <?php echo e(Form::date('From Date',null, array('class' => 'form-control from_date','id'=>'data_picker1',))); ?>

                                    <span id="fromDate" style="color: red;"></span>
                                </div>
                                <div class="col-md-4">
                                    <?php echo e(Form::label('To Date', __('To Date'),['class'=>'col-form-label'])); ?>

                                    <?php echo e(Form::date('To Date',null, array('class' => 'form-control to_date','id'=>'data_picker2',))); ?>

                                    <span id="toDate"  style="color: red;"></span>
                                </div>
                                <div class="col-md-4" id="filter_type" style="padding-top : 38px;">
                                    <button  class="btn btn-primary label-margin generate_button" ><?php echo e(__('Generate')); ?></button>
                                </div>
                            </div>
                            <div id="deals-staff-report" class="mt-3"
                                 data-color="primary" data-height="280">
                            </div>
                        </div>
                    </div>
                    <div id="pipeline-report" class="card">
                        <div class="card-header">
                            <h5><?php echo e(__('Pipeline Report')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div id="deals-piplines-report"
                                     data-color="primary" data-height="280"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>


    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })
        $(".list-group-item").click(function(){
            $('.list-group-item').filter(function(){
                return this.href == id;
            }).parent().removeClass('text-primary');
        });

        function check_theme(color_val) {
            $('#theme_color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }
    </script>


    <script src="<?php echo e(asset('assets/js/plugins/apexcharts.min.js')); ?>"></script>
    <script>
        $(document).ready(function(){
            $("#selectmonth").change(function(){
                var selectedVal = $("#selectmonth option:selected").val();
                //    alert("you select month " + selectedVal);
                var start_month = $('.selectpicker').val();
                $.ajax({
                    url:  "<?php echo e(route('report.deal')); ?>",
                    type: "get",
                    data:{
                        "start_month": start_month,
                        "_token": "<?php echo e(csrf_token()); ?>",
                    },
                    cache: false,
                    success: function(data){

                        $("#deals-monthly").empty();
                        var chartBarOptions = {
                            series: [{
                                name: 'Deal',
                                data: data.data,
                            }],

                            chart: {
                                height: 300,
                                type: 'bar',
                                // type: 'line',
                                dropShadow: {
                                    enabled: true,
                                    color: '#000',
                                    top: 18,
                                    left: 7,
                                    blur: 10,
                                    opacity: 0.2
                                },
                                toolbar: {
                                    show: false
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                width: 2,
                                curve: 'smooth'
                            },
                            title: {
                                text: '',
                                align: 'left'
                            },
                            xaxis: {
                                categories:data.name,

                                title: {
                                    text: '<?php echo e(__("Deal Per Month")); ?>'
                                }
                            },
                            colors: ['#c53da9', '#c53da9'],


                            grid: {
                                strokeDashArray: 4,
                            },
                            legend: {
                                show: false,
                            }

                        };
                        var arChart = new ApexCharts(document.querySelector("#deals-monthly"), chartBarOptions);
                        arChart.render();


                    }
                })
            });
        });


        $(".generate_button").click(function(){
            var from_date = $('.from_date').val();
            if(from_date == ''){
                $("#fromDate").text("Please select date");
            }else{
                $("#fromDate").empty();
            }
            var to_date = $('.to_date').val();
            if(to_date == ''){
                $("#toDate").text("Please select date");
            }else{
                $("#toDate").empty();
            }
            $.ajax({
                url: "<?php echo e(route('report.deal')); ?>",
                type: "get",
                data: {
                    "From_Date": from_date,
                    "To_Date": to_date,
                    "type": 'deal_staff_repport',
                    "_token": "<?php echo e(csrf_token()); ?>",
                },

                cache: false,
                success: function(data) {
                    $("#deals-staff-report").empty();
                    var chartBarOptions = {
                        series: [{
                            name: 'Deal',
                            data: data.data,
                        }],

                        chart: {
                            height: 300,
                            type: 'bar',
                            // type: 'line',
                            dropShadow: {
                                enabled: true,
                                color: '#000',
                                top: 18,
                                left: 7,
                                blur: 10,
                                opacity: 0.2
                            },
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            width: 2,
                            curve: 'smooth'
                        },
                        title: {
                            text: '',
                            align: 'left'
                        },
                        xaxis: {
                            categories: data.name,


                        },
                        colors: ['#6fd944', '#6fd944'],


                        grid: {
                            strokeDashArray: 4,
                        },
                        legend: {
                            show: false,
                        }

                    };
                    var arChart = new ApexCharts(document.querySelector("#deals-staff-report"), chartBarOptions);
                    arChart.render();
                }
            })
        });

        $("#generatebtn").click(function(){
            var from_date = $('.from_date1').val();
            if(from_date == ''){
                $("#fromDate1").text("Please select date");
            }else{
                $("#fromDate1").empty();
            }
            var to_date = $('.to_date1').val();
            if(to_date == ''){
                $("#toDate1").text("Please select date");
            }else{
                $("#toDate1").empty();
            }
            $.ajax({
                url: "<?php echo e(route('report.deal')); ?>",
                type: "get",
                data: {
                    "from_date": from_date,
                    "to_date": to_date,
                    "type": 'client_repport',
                    "_token": "<?php echo e(csrf_token()); ?>",
                },

                cache: false,
                success: function(data) {
                    // console.log(data);
                    $("#deals-clients-report").empty();
                    var chartBarOptions = {
                        series: [{
                            name: 'Deal',
                            data: data.data,
                        }],

                        chart: {
                            height: 300,
                            type: 'bar',
                            // type: 'line',
                            dropShadow: {
                                enabled: true,
                                color: '#000',
                                top: 18,
                                left: 7,
                                blur: 10,
                                opacity: 0.2
                            },
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            width: 2,
                            curve: 'smooth'
                        },
                        title: {
                            text: '',
                            align: 'left'
                        },
                        xaxis: {
                            categories: data.name,


                        },
                        colors: ['#6fd944', '#6fd944'],


                        grid: {
                            strokeDashArray: 4,
                        },
                        legend: {
                            show: false,
                        }

                    };
                    var arChart = new ApexCharts(document.querySelector("#deals-clients-report"), chartBarOptions);
                    arChart.render();
                }
            })
        });

    </script>

    <script>

        var options = {
            series: <?php echo json_encode($devicearray['data']); ?>,
            chart: {
                width: 350,
                type: 'pie',
            },

            colors: ["#35abb6","#ffa21d","#ff3a6e","#6fd943","#5c636a","#181e28","#0288d1"],
            labels: <?php echo json_encode($devicearray['label']); ?>,


            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom',
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#deals-this-week"), options);
        chart.render();

        (function () {
            var chartBarOptions = {
                series: [{
                    name: 'Source',
                    data: <?php echo json_encode($dealsourceeData); ?>,
                }],

                chart: {
                    height: 300,
                    type: 'bar',
                    // type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: <?php echo json_encode($dealsourceName); ?>,

                    title: {
                        text: '<?php echo e(__("Source")); ?>'
                    }
                },
                colors: ['#6fd944', '#6fd944'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#deals-sources-report"), chartBarOptions);
            arChart.render();
        })();

        (function () {
            var chartBarOptions = {
                series: [{
                    name: 'Deal',
                    data: <?php echo json_encode($data); ?>,
                }],

                chart: {
                    height: 300,
                    type: 'bar',
                    // type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: <?php echo json_encode($labels); ?>,

                    title: {
                        text: '<?php echo e(__("Deal Per Month")); ?>'
                    }
                },
                colors: ['#ffa21d', '#ffa21d'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#deals-monthly"), chartBarOptions);
            arChart.render();
        })();

        (function () {
            var chartBarOptions = {
                series: [{
                    name: 'Pipeline',
                    data: <?php echo json_encode($dealpipelineeData); ?>,
                }],

                chart: {
                    height: 300,
                    type: 'bar',
                    // type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: <?php echo json_encode($dealpipelineName); ?>,

                    title: {
                        text: '<?php echo e(__("Pipelines")); ?>'
                    }
                },
                yaxis: {
                    title: {
                        text: '<?php echo e(__("Deals")); ?>'
                    }
                },
                colors: ['#6fd944', '#6fd944'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#deals-piplines-report"), chartBarOptions);
            arChart.render();
        })();

        (function () {
            var chartBarOptions = {
                series: [{
                    name: 'Deal',
                    data: <?php echo json_encode($dealUserData); ?>,
                }],

                chart: {
                    height: 300,
                    type: 'bar',
                    // type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: <?php echo json_encode($dealUserName); ?>,
                    title: {
                        text: '<?php echo e(__("User")); ?>'
                    }


                },
                colors: ['#6fd944', '#6fd944'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#deals-staff-report"), chartBarOptions);
            arChart.render();
        })();

        (function () {
            var chartBarOptions = {
                series: [{
                    name: 'Deal',
                    data: <?php echo json_encode($dealClientData); ?>,
                }],

                chart: {
                    height: 300,
                    type: 'bar',
                    // type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: <?php echo json_encode($dealClientName); ?>,
                },
                colors: ['#6fd944', '#6fd944'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#deals-clients-report"), chartBarOptions);
            arChart.render();
        })();


    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/report/deal.blade.php ENDPATH**/ ?>