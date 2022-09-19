@extends('backend._partial.dashboard')
<style>
    .invoice-to {
        /*width: 401px;*/
        padding: 10px;
        border: 2px solid black;
        margin: 0;
    }
</style>
@section('content')
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Invoice</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Invoice</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="callout callout-info">
                                <h5><i class="fas fa-info"></i> Note:</h5>
                                This page has been enhanced for printing. Click the print button at the bottom of the
                                invoice to test.
                            </div>


                            <!-- Main content -->
                            <div class="invoice p-3 mb-3">
                                <!-- title row -->
                                <div class="row" style="border-bottom: 1px solid #000000;">
                                    <div class="col-12">
                                        <h4 style="float: right">
                                            <img class="float-right" src="{{ asset('uploads/store/' . $store->logo) }}"
                                                alt="logo" height="60px" width="200px"><br><br>
                                            <small class="float-right"> {{ $productSale->created_at }}</small><br>
                                            <small class="float-right">Invoice #{{ $productSale->invoice_no }}</small><br>
                                            <small class="float-right">BIN Reg. NO: 001719214-0201</small><br>
                                        </h4>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- info row -->
                                <div class="row invoice-info">
                                    <div class="col-md-8 invoice-col">
                                        <address>
                                            <strong>To,</strong><br>
                                            <strong>{{ $party->name }}</strong><br>
                                            Address:{{ $party->address }}<br>
                                            Mobile: {{ $party->phone }}<br>
                                            ID NO: {{ $party->id }}<br>
                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-4 invoice-col">
                                        <div class="invoice-to">
                                            <table>
                                                <tr>
                                                    <td
                                                        style="text-align: left;font-size: 16px;border-right: 1px solid #000000">
                                                        Invoice No.</td>
                                                    <td style="text-align: left;font-size: 16px;">
                                                        {{ $productSale->invoice_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="text-align: left;font-size: 16px;border-right: 1px solid #000000">
                                                        DateTime:</td>
                                                    <td style="text-align: left;font-size: 16px;">
                                                        {{ $productSale->created_at }}</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="text-align: left;font-size: 16px;border-right: 1px solid #000000">
                                                        Phone No.</td>
                                                    <td style="text-align: left;font-size: 16px;">02-223362755</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="text-align: left;font-size: 16px;border-right: 1px solid #000000">
                                                        Creditor BY:</td>
                                                    <td style="text-align: left;font-size: 16px;">
                                                        {{ \Illuminate\Support\Facades\Auth::user()->name }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.col -->

                                </div>
                                <!-- /.row -->
                                <h1 style="text-align: center"><strong>Challan</strong></h1>
                                <!-- Table row -->
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr style="background-color: #dddddd">
                                                    <th>SL No.</th>
                                                    <th>Description</th>
                                                    <th>Qty</th>
                                                    <th>Unit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $sum_sub_total = 0;
                                                @endphp
                                                @foreach ($productSaleDetails as $key => $productSaleDetail)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td style="text-align: left">
                                                            {{ $productSaleDetail->product ? $productSaleDetail->product->name : '' }}
                                                        </td>
                                                        <td>{{ $productSaleDetail->qty }}</td>
                                                        <td>{{ $productSaleDetail->product_unit ? $productSaleDetail->product_unit->name : '' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                                <div class="write">&nbsp;</div>
                                <div class="row">
                                    <!-- accepted payments column -->
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6" style="width: 60%; float: left;display: inline-block">
                                                <strong>Received By</strong><br>
                                                <strong>Customer signature</strong>
                                            </div>
                                            <div class="col-md-6"
                                                style="text-align: right; width: 40%; display: inline-block">
                                                <strong>Authorize Signature</strong><br>
                                                <strong>For SIMCO Electronics</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="write">&nbsp;</div>
                                <!-- /.row -->
                                <div class="row footer_div">
                                    <div style="width: 20%;float: left;display: inline-block">
                                        <strong>SIMCO Electronics</strong> <br>
                                        Square Tower, 3-B Level-4
                                        36/6, Mirpur Road
                                        Bashundhara Lane
                                        New Market
                                        Dhaka-1205, Bangladesh.
                                    </div>
                                    <div style="width: 21%;float: left;display: inline-block">
                                        <div style="width: 25%;float: left;">Phone:</div>
                                        <div style="width: 75%;float: left;">+88-02-223362755</div>
                                        <div style="width: 25%;float: left;">Cell:</div>
                                        <div style="width: 75%;float: left;">+88-01711-530918</div>
                                        <div style="width: 25%;float: left;">&nbsp;</div>
                                        <div style="width: 75%;float: left;">+88-01971-530918</div>
                                        <div style="width: 25%;float: left;">Fax:</div>
                                        <div style="width: 75%;float: left;">+88-02-58616169</div>
                                    </div>
                                    <div style="width: 20%;float: left;display: inline-block">
                                        simcodhaka@gmail.com
                                        simco91@gmail.com
                                        info@demo.com.bd
                                        www.demo.com.bd
                                    </div>
                                    <div style="width: 39%;float: left;display: inline-block">
                                        <div style="width: 39%;float: left;">Prime Bank Ltd.</div>
                                        <div style="width: 61%;float: left;">CD A/C #: 02114117001874</div>
                                        <div style="width: 39%;float: left;">BRAC Bank Ltd.</div>
                                        <div style="width: 61%;float: left;">CD A/C #: 1524204051833001</div>
                                        <div style="width: 39%;float: left;">NCC Bank Ltd.</div>
                                        <div style="width: 61%;float: left;">CD A/C #: 00430210000068</div>
                                        <div style="width: 39%;float: left;">Trust Bank Ltd.</div>
                                        <div style="width: 61%;float: left;">CD A/C #: 00530210005141</div>
                                        <div style="width: 39%;float: left;">Agrani Bank Ltd.</div>
                                        <div style="width: 61%;float: left;">CD A/C #: 0200010401754</div>
                                        <div style="width: 39%;float: left;">Union Bank Ltd.</div>
                                        <div style="width: 61%;float: left;">CD A/C #: 0941010000128</div>
                                    </div>
                                </div>
                                <!-- this row will not appear when printing -->
                                <div class="row no-print">
                                    <div class="col-12">
                                        <a href="{{ route('productSales-challan-print', $productSale->id) }}"
                                            target="_blank" class="btn btn-success float-right"><i class="fas fa-print"></i>
                                            Print</a>
                                        {{-- <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit --}}
                                        {{-- Payment --}}
                                        {{-- </button> --}}
                                        {{-- <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;"> --}}
                                        {{-- <i class="fas fa-download"></i> Generate PDF --}}
                                        {{-- </button> --}}
                                    </div>
                                </div>
                            </div>
                            <!-- /.invoice -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/demo.js') }}"></script>
@endsection
