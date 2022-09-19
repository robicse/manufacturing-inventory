<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

<!-- Printable area end -->
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4></h4>
                </div>
            </div>
            <div id="printArea">
                <style>
                    .panel-body {
                        min-height: 1000px !important;
                        font-size: 16px !important;
                        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                        font-weight: inherit;
                    }

                    .invoice {
                        border-collapse: collapse;
                        width: 100%;
                    }

                    .invoice th {
                        /*border-top: 1px solid #000;*/
                        /*border-bottom: 1px solid #000;*/
                        border: 1px solid #000;
                    }

                    .invoice td {
                        text-align: center;
                        font-size: 16px;
                        border: 1px solid #000;
                    }

                    .invoice-logo {
                        margin-right: 0;
                    }

                    .invoice-logo>img,
                    .invoice-logo>span {
                        float: right !important;
                    }

                    .invoice-to {
                        border: 1px solid black;
                        margin: 0;
                    }

                    .footer_div {
                        position: absolute;
                        bottom: 0 !important;
                        border-top: 1px solid #000000;
                        width: 100%;
                        font-size: 10px !important;
                        padding-bottom: 8px;
                    }

                    /* default settings */
                    /*.page {*/
                    /*    page-break-after: always;*/
                    /*}*/

                    @page {
                        size: A4;
                        /*size: Letter;*/
                        /*margin: 0px !important;*/
                        /*margin: 16px 100px !important;*/
                        margin: 16px 50px !important;
                    }

                    /*@media screen {*/
                    /*    .page-header {display: none;}*/
                    /*    .page-footer {display: none;}*/

                    /*}*/

                    /*@media print {*/
                    /*    table { page-break-inside:auto }*/
                    /*    tr    { page-break-inside:auto; page-break-after:auto }*/
                    /*    thead { display:table-header-group }*/
                    /*    tfoot { display:table-footer-group }*/
                    /*    button {display: none;}*/
                    /*    body {margin: 0;}*/
                    /*}*/
                    /* default settings */
                </style>
                <div class="panel-body">
                    <div class="row" style="padding-top: 22px;">
                        <div class="col-md-6" style="width: 80%; float: left;display: inline-block">&nbsp;</div>
                        <div class="col-md-6" style="text-align: right; width: 20%; display: inline-block">
                            <div class="invoice-logo">
                                <img src="{{ asset('uploads/store/' . $store->logo) }}" alt="logo" height="60px"
                                    width="200px">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row"> --}}
                    {{-- <div class="col-md-6" style="width: 100%; float: left;display: inline-block">&nbsp;</div> --}}
                    {{-- <div class="col-md-6" style="text-align: right; width: 100%; display: inline-block"> --}}
                    {{-- <div class="invoice-logo"> --}}
                    {{-- <span style="font-size: 16px;"> {{date('d-m-Y')}}</span><br> --}}
                    {{-- <small class="float-right" style="font-size: 16px;">Invoice #{{$productSale->invoice_no}}</small><br> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                    <div>&nbsp;</div>
                    <div class="row">
                        <div class="col-md-6" style="width: 60%; float: left;display: inline-block">
                            <strong>To,</strong><br>
                            <strong>{{ $party->name }}</strong><br>
                            Address:{{ $party->address }}<br>
                            Mobile: {{ $party->phone }}<br>
                            ID NO: {{ $party->id }}<br>
                        </div>
                        <div class="col-md-6" style="text-align: right; width: 40%; display: inline-block">
                            <div class="invoice-to">
                                <table>
                                    <tr>
                                        <td style="text-align: left;font-size: 16px;border-right: 1px solid #000000">
                                            Invoice No.</td>
                                        <td style="text-align: left;font-size: 16px;">{{ $productSale->invoice_no }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;font-size: 16px;border-right: 1px solid #000000">
                                            DateTime:</td>
                                        <td style="text-align: left;font-size: 16px;">{{ $productSale->created_at }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;font-size: 16px;border-right: 1px solid #000000">BIN
                                            Reg. NO:</td>
                                        <td style="text-align: left;font-size: 16px;">001719214-0201</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;font-size: 16px;border-right: 1px solid #000000">
                                            Phone No.</td>
                                        <td style="text-align: left;font-size: 16px;">02-223362755</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;font-size: 16px;border-right: 1px solid #000000">
                                            Creditor BY:</td>
                                        <td style="text-align: left;font-size: 16px;">
                                            {{ \Illuminate\Support\Facades\Auth::user()->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br />
                    <br />
                    <h1 style="text-align: center"><strong>Challan</strong></h1>
                    <table class="invoice">
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
                                    <td style="text-align: left">{{ $productSaleDetail->product->name }}</td>
                                    <td>{{ $productSaleDetail->qty }}</td>
                                    <td>{{ $productSaleDetail->product_unit->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row" style="margin-top: 10%">
                        <div class="col-md-6" style="width: 60%; float: left;display: inline-block">
                            <strong>Received By</strong><br>
                            <strong>Customer signature</strong>
                        </div>
                        <div class="col-md-6" style="text-align: right; width: 40%; display: inline-block">
                            <strong>Authorize Signature</strong><br>
                            <strong>For SIMCO Electronics</strong>
                        </div>
                    </div>

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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>

<script type="text/javascript">
    window.addEventListener("load", window.print());
</script>
