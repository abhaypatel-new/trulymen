@extends('layouts.admin')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@push('script-page')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://hammerjs.github.io/dist/hammer.js"></script>
    <script>
        @if(\Auth::user()->can('show account dashboard'))
        (function () {
            var chartBarOptions = {
                series: [
                    {
                        name: "{{__('Income')}}",
                        data:{!! json_encode($incExpLineChartData['income']) !!}
                    },
                    {
                        name: "{{__('Expense')}}",
                        data: {!! json_encode($incExpLineChartData['expense']) !!}
                    }
                ],

                chart: {
                    height: 250,
                    type: 'area',
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
                    categories:{!! json_encode($incExpLineChartData['day']) !!},
                    title: {
                        text: '{{ __("Date") }}'
                    }
                },
                colors: ['#6fd944', '#ff3a6e'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                // markers: {
                //     size: 4,
                //     colors: ['#6fd944', '#FF3A6E'],
                //     opacity: 0.9,
                //     strokeWidth: 2,
                //     hover: {
                //         size: 7,
                //     }
                // },
                yaxis: {
                    title: {
                        text: '{{ __("Amount") }}'
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#cash-flow"), chartBarOptions);
            arChart.render();
        })();

        (function () {
            var options = {
                chart: {
                    height: 180,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                series: [{
                    name: "{{__('Income')}}",
                    data: {!! json_encode($incExpBarChartData['income']) !!}
                }, {
                    name: "{{__('Expense')}}",
                    data: {!! json_encode($incExpBarChartData['expense']) !!}
                }],
                xaxis: {
                    categories: {!! json_encode($incExpBarChartData['month']) !!},
                },
                colors: ['#3ec9d6', '#FF3A6E'],
                fill: {
                    type: 'solid',
                },
                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'right',
                },
                // markers: {
                //     size: 4,
                //     colors:  ['#3ec9d6', '#FF3A6E',],
                //     opacity: 0.9,
                //     strokeWidth: 2,
                //     hover: {
                //         size: 7,
                //     }
                // }
            };
            var chart = new ApexCharts(document.querySelector("#incExpBarChart"), options);
            chart.render();
        })();

        (function () {
            var options = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($expenseCatAmount) !!},
                colors: {!! json_encode($expenseCategoryColor) !!},
                labels: {!! json_encode($expenseCategory) !!},
                legend: {
                    show: true
                }
            };
            var chart = new ApexCharts(document.querySelector("#expenseByCategory"), options);
            chart.render();
        })();

        (function () {
            var options = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($incomeCatAmount) !!},
                colors: {!! json_encode($incomeCategoryColor) !!},
                labels:  {!! json_encode($incomeCategory) !!},
                legend: {
                    show: true
                }
            };
            var chart = new ApexCharts(document.querySelector("#incomeByCategory"), options);
            chart.render();
        })();

        (function () {
            var options = {
                series: [{{ round($storage_limit,2) }}],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -20,
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: '97%',
                            margin: 5, // margin is in pixels
                        },
                        dataLabels: {
                            name: {
                                show: true
                            },
                            value: {
                                offsetY: -50,
                                fontSize: '20px'
                            }
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -10
                    }
                },
                colors: ["#6FD943"],
                labels: ['Used'],
            };
            var chart = new ApexCharts(document.querySelector("#limit-chart"), options);
            chart.render();
        })();
        (function () {
    var options = {
        series: [14, 23, 21, 17, 15, 10, 12, 17, 21],
        chart: {
            type: 'polarArea',
        },
        colors: ['#FF5733', '#FFC300', '#36A2EB', '#4CAF50', '#FF6384', '#8e5ea2', '#3cba9f', '#e8c3b9', '#c45850'],
        fill: {
            opacity: 1, // Adjust opacity for more vibrant colors
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#pie-chart"), options);
    chart.render();
})();




    (function () {
    var options = {
        series: [{
            data: [200, 230, 248, 270, 340]
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false,
                colors: {
                    backgroundBarColors: ['#E8F0FF'],
                    ranges: [{
                        from: 0,
                        to: 10000,
                        color: 'orangered' // Set the color of bars to orangered
                    }]
                }
            }
        },
        dataLabels: {
            enabled: true
        },
        yaxis: {
            show: false // Disable y-axis name
        },
        xaxis: {
            categories: ['POINT LEVEL SWITCHES', 'LEVEL TRANSMITTERS', 'INDICATOR CONTROLLERS', 'PRESSURE TRANSMITTERS', 'Shield Sense Capacitance'],
            labels: {
                rotate: 0, // Set x-axis label rotation to 0 degrees (not tilted)
                // offsetY: 0, // Adjust label offset if needed
                style: {
                    fontSize: '12px' // Optional: Adjust label font size if needed
                }
            },
            position: 'top'
        }
    };

    var chart = new ApexCharts(document.querySelector("#bar_chart"), options);
    chart.render();
})();
 
(function () {
    var options = {
        series: [{
            data: [44, 55, 41, 64, 22, 43, 21],
            color: 'orangered'
        }, {
            data: [53, 32, 33, 52, 13, 44, 32]
        }],
        chart: {
            type: 'bar',
            height: 430
        },
        plotOptions: {
            bar: {
                horizontal: false,
                dataLabels: {
                    position: 'top',
                },
            }
        },
        dataLabels: {
            enabled: true,
            offsetX: -6,
            style: {
                fontSize: '12px',
                colors: ['#fff']
            }
        },
        stroke: {
            show: true,
            width: 1,
            colors: ['#fff']
        },
        tooltip: {
            shared: true,
            intersect: false
        },
        xaxis: {
            categories: ['December', 'January', 'February', 'March', 'April', 'May', 'June'],
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return value > 1000 ? (value / 1000).toFixed(0) + 'k' : value;
                },
                style: {
                    fontSize: '12px',
                    fontFamily: 'Arial, sans-serif',
                    fontWeight: 400,
                    color: '#333'
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#pendingpaymentchart"), options);
    chart.render();
})();

        

        @endif
 
    </script>
    <script>

let currentIndex = 0;
// Function to generate date labels for the next 7 days
function updateCalendarHeading() {
    const calenderHead = document.getElementById('task_calender_heading');
    const currentDate = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    const month = monthNames[currentDate.getMonth()];
    const year = currentDate.getFullYear();

    calenderHead.textContent = `${month},${year}`;
}

// Call the function to update the calendar heading initially
updateCalendarHeading();
function generateDateLabels() {
    const currentDate = new Date();
    const labels = [];
    for (let i = -7; i <= 7; i++) {
        const date = new Date(currentDate);
        date.setDate(date.getDate() + i);
        labels.push(formatDate(date));
    }
    return labels;
}

// Function to format a date object into a string like "April 20, 2024"
function formatDate(date) {
    return `${date.toLocaleString('default', { month: 'long' })} ${date.getDate()}, ${date.getFullYear()}`;
}

// Generate date labels for the next 7 days
const dateLabels = generateDateLabels();

// Function to create date cards in the carousel
function createDateCards() {
    const dateSliderCards = document.getElementById("date-slider-cards");
    dateSliderCards.innerHTML = ''; // Clear existing cards

    dateLabels.forEach((label, index) => {
        const cardItem = document.createElement('div');
        cardItem.classList.add('card-item');
        const date = new Date(label);
        const formattedDate = date.toLocaleString('en-US', { weekday: 'short', day: '2-digit' });
        cardItem.textContent = formattedDate;
        if (index === currentIndex) {
            cardItem.classList.add('active');
        }
        dateSliderCards.appendChild(cardItem);
    });

    // Scroll the slider container to center the selected date card
    const selectedCard = dateSliderCards.querySelector('.active');
    if (selectedCard) {
        const cardWidth = selectedCard.offsetWidth;
        const scrollLeft = (selectedCard.offsetLeft + cardWidth / 2) - dateSliderCards.offsetWidth / 2;
        dateSliderCards.scrollLeft = scrollLeft;
    }
}

// Function to slide date cards when left or right arrows are clicked
function slideCards(direction) {
    const maxIndex = dateLabels.length - 1;
    currentIndex = (currentIndex + direction) % dateLabels.length;
    if (currentIndex < 0) {
        currentIndex = maxIndex;
    }
    document.getElementById("date-label").textContent = dateLabels[currentIndex];
    createDateCards();
}

// Initial setup
createDateCards();


    </script>

    <script>
        const $ = (selector) => {
  return document.querySelector(selector);
};

function next() {
  if ($(".hide")) {
    $(".hide").remove();
  }

  /* Step */

  if ($(".prev-calender-dashboard")) {
    $(".prev-calender-dashboard").classList.add("hide");
    $(".prev-calender-dashboard").classList.remove("prev-calender-dashboard");
  }

  $(".act-calender-dashboard").classList.add("prev-calender-dashboard");
  $(".act-calender-dashboard").classList.remove("act-calender-dashboard");

  $(".next-calender-dashboard").classList.add("act-calender-dashboard");
  $(".next-calender-dashboard").classList.remove("next-calender-dashboard");

  /* New Next */

  $(".new-next").classList.remove("new-next");

  const addedEl = document.createElement("li");

  $(".list-calender-dashboard").appendChild(addedEl);
  addedEl.classList.add("next-calender-dashboard", "new-next");
}

function prev() {
  $(".new-next").remove();

  /* Step */

  $(".next-calender-dashboard").classList.add("new-next");

  $(".act-calender-dashboard").classList.add("next-calender-dashboard");
  $(".act-calender-dashboard").classList.remove("act-calender-dashboard");

  $(".prev-calender-dashboard").classList.add("act-calender-dashboard");
  $(".prev-calender-dashboard").classList.remove("prev-calender-dashboard");

  /* New Prev */

  $(".hide").classList.add("prev-calender-dashboard");
  $(".hide").classList.remove("hide");

  const addedEl = document.createElement("li");

  $(".list-calender-dashboard").insertBefore(addedEl, $(".list-calender-dashboard").firstChild);
  addedEl.classList.add("hide");
}

slide = (element) => {
  /* Next slide */

  if (element.classList.contains("next-calender-dashboard")) {
    next();

    /* Previous slide */
  } else if (element.classList.contains("prev-calender-dashboard")) {
    prev();
  }
};

const slider = $(".list-calender-dashboard"),
  swipe = new Hammer($(".swipe-calender-dashboard"));

slider.onclick = (event) => {
  slide(event.target);
};

swipe.on("swipeleft", (ev) => {
  next();
});

swipe.on("swiperight", (ev) => {
  prev();
});

    </script>
    <script>
        function selectFilter(element){
            document.querySelectorAll('.filter-calender').forEach(item =>{
                item.classList.remove('active-filtered')
            })
            element.classList.add('active-filtered')
        }
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Account')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-7">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card INR-card-accountdashboard">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-INR-accountdashboard">
                                                <i class='bx bx-bar-chart-square'></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Customers')}}</h6>
                                            <h3 class="mb-0">INR 1K</h3>
                                            <small>+8% from yesterday</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="card customer-card-accountdashboard">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-customer-accountdashboard">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Customers')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countCustomers()}}</h3>
                                            <small>+5% from yesterday</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="card order-card-accountdashboard">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-order-accountdashboard">
                                                <i class='bx bx-message-edit'></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Order')}}</h6>
                                            <h3 class="mb-0">5</h3>
                                            <small>+1,2% from yesterday</small>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Vendors')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countVenders()}}
                                            </h3>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-lg-3 col-6">
                                    <div class="card invoice-card-accountdashboard">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-invoice-accountdashboard">
                                                <i class="ti ti-report-money"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Invoicing')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countInvoices()}} </h3>
                                            <small>0,5% from yesterday</small>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti ti-report-money"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2">{{__('Total')}}</p>
                                            <h6 class="mb-3">{{__('Bills')}}</h6>
                                            <h3 class="mb-0">{{\Auth::user()->countBills()}} </h3>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{__('Income & Expense')}}
                                        <span class="float-end text-muted">{{__('Current Year').' - '.$currentYear}}</span>
                                    </h5>

                                </div>
                                <div class="card-body">
                                    <div id="incExpBarChart"></div>
                                </div>
                            </div>
                        </div>
                        {{-- <di class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Account Balance')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Bank')}}</th>
                                                <th>{{__('Holder Name')}}</th>
                                                <th>{{__('Balance')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($bankAccountDetail as $bankAccount)

                                                <tr class="font-style">
                                                    <td>{{$bankAccount->bank_name}}</td>
                                                    <td>{{$bankAccount->holder_name}}</td>
                                                    <td>{{\Auth::user()->priceFormat($bankAccount->opening_balance)}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                            <h6>{{__('there is no account balance')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </di> --}}
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="mt-1 mb-0">{{__('Total Dead Stock')}}</h5>
                                            <small>Last 1 Months</small>
                                        </div>
                                        <div class="view-button-accountdashboard">
                                            <button>view</button>
                                            
                                            <i class='bx bx-chevron-right side-arrow-view-dashboard'></i>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    {{-- <div class="table-responsive"> --}}
                                       <div id="bar_chart"></div>
                                    {{-- </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                    <h5 class="mt-1 mb-0">{{__('Total Pending Payment')}}</h5>
                                    <div class="view-button-accountdashboard">
              <span><i class='bx bxs-circle' style='color:#FF5000'  ></i>Pending Payment</span>  
              <span><i class='bx bxs-circle' style="color: #00E096;"></i>Total Sales</span>  
                                    </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="pendingpaymentchart">
                                   
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Latest Income')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Date')}}</th>
                                                <th>{{__('Customer')}}</th>
                                                <th>{{__('Amount Due')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($latestIncome as $income)
                                                <tr>
                                                    <td>{{\Auth::user()->dateFormat($income->date)}}</td>
                                                    <td>{{!empty($income->customer)?$income->customer->name:'-'}}</td>
                                                    <td>{{\Auth::user()->priceFormat($income->amount)}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                            <h6>{{__('There is no latest income')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Latest Expense')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Date')}}</th>
                                                <th>{{__('Vendor')}}</th>
                                                <th>{{__('Amount Due')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($latestExpense as $expense)

                                                <tr>
                                                    <td>{{\Auth::user()->dateFormat($expense->date)}}</td>
                                                    <td>{{!empty($expense->vender)?$expense->vender->name:'-'}}</td>
                                                    <td>{{\Auth::user()->priceFormat($expense->amount)}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-center">
                                                            <h6>{{__('There is no latest expense')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Recent Invoices')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('Customer')}}</th>
                                                <th>{{__('Issue Date')}}</th>
                                                <th>{{__('Due Date')}}</th>
                                                <th>{{__('Amount')}}</th>
                                                <th>{{__('Status')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($recentInvoice as $invoice)
                                                <tr>
                                                    <td>{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}</td>
                                                    <td>{{!empty($invoice->customer_name)? $invoice->customer_name:'' }} </td>
                                                    <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                                    <td>{{ Auth::user()->dateFormat($invoice->due_date) }}</td>
                                                    <td>{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                                    <td>
                                                        @if($invoice->status == 0)
                                                            <span class="p-2 px-3 rounded badge status_badge bg-secondary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                        @elseif($invoice->status == 1)
                                                            <span class="p-2 px-3 rounded badge status_badge bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                        @elseif($invoice->status == 2)
                                                            <span class="p-2 px-3 rounded badge status_badge bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                        @elseif($invoice->status == 3)
                                                            <span class="p-2 px-3 rounded badge status_badge bg-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                        @elseif($invoice->status == 4)
                                                            <span class="p-2 px-3 rounded badge status_badge bg-primary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="text-center">
                                                            <h6>{{__('There is no recent invoice')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Recent Bills')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('Vendor')}}</th>
                                                <th>{{__('Bill Date')}}</th>
                                                <th>{{__('Due Date')}}</th>
                                                <th>{{__('Amount')}}</th>
                                                <th>{{__('Status')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($recentBill as $bill)
                                                <tr>
                                                    <td>{{\Auth::user()->billNumberFormat($bill->bill_id)}}</td>
                                                    <td>{{!empty($bill->vender_name)? $bill->vender_name : '-' }} </td>
                                                    <td>{{ Auth::user()->dateFormat($bill->bill_date) }}</td>
                                                    <td>{{ Auth::user()->dateFormat($bill->due_date) }}</td>
                                                    <td>{{\Auth::user()->priceFormat($bill->getTotal())}}</td>
                                                    <td>
                                                        @if($bill->status == 0)
                                                            <span class="p-2 px-3 rounded badge bg-secondary">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 1)
                                                            <span class="p-2 px-3 rounded badge bg-warning">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 2)
                                                            <span class="p-2 px-3 rounded badge bg-danger">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 3)
                                                            <span class="p-2 px-3 rounded badge bg-info">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 4)
                                                            <span class="p-2 px-3 rounded badge bg-primary">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="text-center">
                                                            <h6>{{__('There is no recent bill')}}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-xxl-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Total Pending order for Manufactring')}}</h5>
                                    <small>Last 1 Months</small>
                                </div>
                                <div class="card-body">
                                    <div>
                                      <h1>042</h1>
                                      <small>Total Manufacturing Order</small>
                                      <div>
                                        <img src="{{asset('assets/images/dashboard/sandclock.png')}}" alt="">
                                       5 pending
                                    </div>
                                      <div>
                                        <img src="{{asset('assets/images/dashboard/ongoing.png')}}" alt="">
                                        12 Ongoing
                                    </div>
                                      <div>
                                        <img src="{{asset('assets/images/dashboard/completed.png')}}" alt="">
                                        Completed
                                    </div>
                                    </div>
                 
                                        <div id="pie-chart"></div>
                            
                                </div>
                            </div>
                            {{-- <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Cashflow')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div id="cash-flow"></div>
                                </div>
                            </div> --}}


{{-- ============================================================================================ --}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mt-1 mb-0">{{__('Recent Alerts')}}</h5>
                                        <div class="view-button-accountdashboard">
                                            <button>view</button>
                                           
                                                <i class='bx bx-chevron-right side-arrow-view-dashboard'></i>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column align-center gap-2">
                                       <div class="recent-alerts-section-1">
                                        <h5><i class='bx bxs-circle'></i>Low Raw Material in Mechanical Department.</h5>
                                         <p>Norem ipsum dolor sit amet, consectetur adipiscing elit. Nunc vulputate libero et velit interdum.</p>
                                         <small>07:30 AM Tue 20/02/24</small>
                                       </div>

                                       <div class="recent-alerts-section-2">
                                        <h5><i class='bx bxs-circle'></i>Broken Light Bulb</h5>
                                      
                                         <small>10:30 AM Mon 19/02/24</small>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ================================================================= --}}
{{-- ============================================================================================ --}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex justify-content-between gap-2 todo-list-section-dashboard">
                                            <img src="{{asset('assets/images/dashboard/ToDoDashboard.png')}}" alt="Todo">
                                            <h4  class="mt-1 mb-0">{{('To do')}}</h4>
                                        </div>
                                        <div class="addnew-button-accountdashboard">
                                          <i class='bx bx-plus plus-icon-addnew-dashboard'></i>
                                            <button>Add new</button>                                         
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column align-center gap-2">
                                       <div class="todo-list-dashboard-content">
                                       <ul>
                                        <li>Send Quotation to Mr. John on 20 Oct at 5 O’ Clock </li>
                                        <li>Send Quotation to Mr. John on 20 Oct at 5 O’ Clock </li>
                                        <li>Send Quotation to Mr. John on 20 Oct at 5 O’ Clock </li>
                                        <li>Send Quotation to Mr. John on 20 Oct at 5 O’ Clock </li>
                                       </ul>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ================================================================= --}}
{{-- ============================================================================================ --}}
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div>
                <h5 class="mt-1 mb-0" id="task_calender_heading"></h5>
                <div class="filter-calender-main">
                    <div class="filter-calender" onclick="selectFilter(this)">Days</div>
                    <div class="filter-calender" onclick="selectFilter(this)">Week</div>
                    <div class="filter-calender" onclick="selectFilter(this)">Month</div>
                </div>
            </div>
            <div class="view-button-accountdashboard">
                <i class='bx bx-chevron-left side-arrow-view-dashboard' onclick="slideCards(-1)"></i>
                <button id="date-label">Today</button>
                <i class='bx bx-chevron-right side-arrow-view-dashboard' onclick="slideCards(1)"></i>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="date-carousel-calender">
            <i class='bx bx-calendar'></i>
            <div id="date-slider-cards" class="card-carousel">
                <!-- Add your date cards here -->
                <!-- Example: <div class="card-item">April 20, 2024</div> -->
            </div>
        </div>
                  <div class="main-container-task-card-dashboard">

                      <div class="calender-task-card-dashboard">
                          <ul class="list-calender-dashboard">
                <li  class="hide-calender-dashboard"></li>
                <li  class="prev-calender-dashboard"></li>
                <li  class="act-calender-dashboard">
                    
                </li>
                <li  class="next-calender-dashboard"></li>
                <li class="next-calender-dashboard new-next"></li>
              </ul>
              
              <div class="swipe-calender-dashboard"></div>
            </div>
            <div class="Add-button-for-slider-task-calender"><i class='bx bx-plus-circle' style='color:#D9D9D9' ></i></div>
        </div>
    </div>
</div>


                        {{-- ================================================================= --}}
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mt-1 mb-0">{{__('Income Vs Expense')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-report-money"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Income Today')}}</p>
                                                    <h4 class="mb-0 text-primary">{{\Auth::user()->priceFormat(\Auth::user()->todayIncome())}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti ti-file-invoice"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Expense Today')}}</p>
                                                    <h4 class="mb-0 text-info">{{\Auth::user()->priceFormat(\Auth::user()->todayExpense())}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti ti-report-money"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Income This Month')}}</p>
                                                        <h4 class="mb-0 text-warning">{{\Auth::user()->priceFormat(\Auth::user()->incomeCurrentMonth())}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 my-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti ti-file-invoice"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="text-muted text-sm mb-0">{{__('Expense This Month')}}</p>
                                                    <h4 class="mb-0 text-danger">{{\Auth::user()->priceFormat(\Auth::user()->expenseCurrentMonth())}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{__('Storage Limit')}}
{{--                                        <span class="float-end text-muted">{{__('Year').' - '.$currentYear}}</span>
                                        <small class="float-end text-muted">{{ $users->storage_limit . 'MB' }} / {{ $plan->storage_limit . 'MB' }}</small>--}}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="limit-chart"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{__('Income By Category')}}
                                        <span class="float-end text-muted">{{__('Year').' - '.$currentYear}}</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="incomeByCategory"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{__('Expense By Category')}}
                                        <span class="float-end text-muted">{{__('Year').' - '.$currentYear}}</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="expenseByCategory"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-body">

                                    <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#invoice_weekly_statistics" role="tab" aria-controls="pills-home" aria-selected="true">{{__('Invoices Weekly Statistics')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#invoice_monthly_statistics" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Invoices Monthly Statistics')}}</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="invoice_weekly_statistics" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <div class="table-responsive">
                                                <table class="table align-items-center mb-0 ">
                                                    <tbody class="list">
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Invoice Generated')}}</p>

                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyInvoice['invoiceTotal'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyInvoice['invoicePaid'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Due')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyInvoice['invoiceDue'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="invoice_monthly_statistics" role="tabpanel" aria-labelledby="pills-profile-tab">
                                            <div class="table-responsive">
                                                <table class="table align-items-center mb-0 ">
                                                    <tbody class="list">
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Invoice Generated')}}</p>

                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyInvoice['invoiceTotal'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyInvoice['invoicePaid'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Due')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyInvoice['invoiceDue'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-body">

                                    <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#bills_weekly_statistics" role="tab" aria-controls="pills-home" aria-selected="true">{{__('Bills Weekly Statistics')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#bills_monthly_statistics" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Bills Monthly Statistics')}}</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="bills_weekly_statistics" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <div class="table-responsive">
                                                <table class="table align-items-center mb-0 ">
                                                    <tbody class="list">
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Bill Generated')}}</p>

                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyBill['billTotal'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyBill['billPaid'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Due')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($weeklyBill['billDue'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="bills_monthly_statistics" role="tabpanel" aria-labelledby="pills-profile-tab">
                                            <div class="table-responsive">
                                                <table class="table align-items-center mb-0 ">
                                                    <tbody class="list">
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Bill Generated')}}</p>

                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyBill['billTotal'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Paid')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyBill['billPaid'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="mb-0">{{__('Total')}}</h5>
                                                            <p class="text-muted text-sm mb-0">{{__('Due')}}</p>
                                                        </td>
                                                        <td>
                                                            <h4 class="text-muted">{{\Auth::user()->priceFormat($monthlyBill['billDue'])}}</h4>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{__('Goal')}}</h5>
                        </div>
                        <div class="card-body">
                            @forelse($goals as $goal)
                                @php
                                    $total= $goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['total'];
                                    $percentage=$goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['percentage'];
                                    $per=number_format($goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['percentage'], Utility::getValByName('decimal_number'), '.', '');
                                @endphp
                                <div class="card border-success border-2 border-bottom-0 border-start-0 border-end-0">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <label class="form-check-label d-block" for="customCheckdef1">
                                                <span>
                                                    <span class="row align-items-center">
                                                        <span class="col">
                                                            <span class="text-muted text-sm">{{__('Name')}}</span>
                                                            <h6 class="text-nowrap mb-3 mb-sm-0">{{$goal->name}}</h6>
                                                        </span>
                                                        <span class="col">
                                                            <span class="text-muted text-sm">{{__('Type')}}</span>
                                                            <h6 class="mb-3 mb-sm-0">{{ __(\App\Models\Goal::$goalType[$goal->type]) }}</h6>
                                                        </span>
                                                        <span class="col">
                                                            <span class="text-muted text-sm">{{__('Duration')}}</span>
                                                            <h6 class="mb-3 mb-sm-0">{{$goal->from .' To '.$goal->to}}</h6>
                                                        </span>
                                                        <span class="col">
                                                            <span class="text-muted text-sm">{{__('Target')}}</span>
                                                            <h6 class="mb-3 mb-sm-0">{{\Auth::user()->priceFormat($total).' of '. \Auth::user()->priceFormat($goal->amount)}}</h6>
                                                        </span>
                                                        <span class="col">
                                                            <span class="text-muted text-sm">{{__('Progress')}}</span>
                                                            <h6 class="mb-2 d-block">{{number_format($goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['percentage'], Utility::getValByName('decimal_number'), '.', '')}}%</h6>
                                                            <div class="progress mb-0">
                                                                @if($per<=33)
                                                                    <div class="progress-bar bg-danger" style="width: {{$per}}%"></div>
                                                                @elseif($per>=33 && $per<=66)
                                                                    <div class="progress-bar bg-warning" style="width: {{$per}}%"></div>
                                                                @else
                                                                    <div class="progress-bar bg-primary" style="width: {{$per}}%"></div>
                                                                @endif
                                                            </div>
                                                        </span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="card pb-0">
                                    <div class="card-body text-center">
                                        <h6>{{__('There is no goal.')}}</h6>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        if(window.innerWidth <= 500)
        {
            $('p').removeClass('text-sm');
        }
    </script>
@endpush
