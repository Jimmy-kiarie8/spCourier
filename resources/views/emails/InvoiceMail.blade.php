@component('mail::message')
{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
{{-- {{ $subject }} --}}

@component('mail::panel')
<div class="col-sm-4">
    <div class="form-group">
        <label>Title</label>
        <p>{{$invoice->title}}</p>
    </div><hr>
    <div class="row">
        <div class="col-sm-6">
            <label>Invoice Date</label>
            <p>{{$invoice->invoice_date}}</p>
        </div>
        <div class="col-sm-6">
            <label>Due Date</label>
            <p>{{$invoice->due_date}}</p>
        </div>
    </div>
</div><hr>
<div class="col-sm-4">
    <div class="form-group">
        <label>Invoice No.</label>
        <p>{{$invoice->invoice_no}}</p>
    </div>
    {{-- <div class="form-group">
        <label>Grand Total</label>
        <p>Ksh{{$invoice->grand_total}}</p>
    </div> --}}
</div><hr>
<div class="col-sm-4">
    <div class="form-group">
        <label>Client Id</label>
        <p>{{$invoice->client}}</p>
    </div>
    {{-- <div class="form-group">
        <label>Client Address</label>
        <pre class="pre">{{$invoice->client_address}}</pre>
    </div> --}}
</div>
@endcomponent

@component('mail::table')
| Product name       | Price         | Quantity  | Total 	 |
| ------------------ |:-------------:| ---------:|:---------:|
@foreach  ($invoice->products as $product)
| {{$product->name}}   | {{$product->price}} | {{$product->qty}} | {{$product->qty * $product->price}} |
@endforeach
|   |  |  Grand Total | {{$invoice->grand_total}} |
|   |  |  VAT | {{$invoice->vat}} |
@endcomponent
{{-- 
<div class="panel panel-default col-md-12">
    <div class="panel-heading">
        <div class="clearfix">
            <span class="panel-title">Invoice</span>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Invoice No.</label>
                    <p>{{$invoice->invoice_no}}</p>
                </div>
                <div class="form-group">
                    <label>Grand Total</label>
                    <p>Ksh{{$invoice->grand_total}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Client</label>
                    <p>{{$invoice->client}}</p>
                </div>
                <div class="form-group">
                    <label>Client Address</label>
                    <pre class="pre">{{$invoice->client_address}}</pre>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Title</label>
                    <p>{{$invoice->title}}</p>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Invoice Date</label>
                        <p>{{$invoice->invoice_date}}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>Due Date</label>
                        <p>{{$invoice->due_date}}</p>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            	@foreach ($invoice->products as $product)
	                <tr>
	                    <td class="table-name">{{$product->name}}</td>
	                    <td class="table-price">Ksh{{$product->price}}</td>
	                    <td class="table-qty">{{$product->qty}}</td>
	                    <td class="table-total text-right">Ksh{{$product->qty * $product->price}}</td>
	                </tr>
            	@endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="table-empty" colspan="2"></td>
                    <td class="table-label">Sub Total</td>
                    <td class="table-amount">Ksh{{$invoice->sub_total}}</td>
                </tr>
                <tr>
                    <td class="table-empty" colspan="2"></td>
                    <td class="table-label">Discount</td>
                    <td class="table-amount">Ksh{{$invoice->discount}}</td>
                </tr>
                <tr>
                    <td class="table-empty" colspan="2"></td>
                    <td class="table-label">Grand Total</td>
                    <td class="table-amount">Ksh{{$invoice->grand_total}}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div> --}}
{{-- @endcomponent --}}

{{--  @component('mail::button', ['url' => ''])
Button Text
@endcomponent
 --}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent

{{-- @endsection() --}}
