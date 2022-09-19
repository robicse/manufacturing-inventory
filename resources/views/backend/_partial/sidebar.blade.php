<aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="{!! asset('backend/user.png') !!}"
                                        alt="User Image" width="60px">
        <div>
            <p class="app-sidebar__user-name">{{ Auth::user()->roles[0]->name }}</p>
            <p class="app-sidebar__user-designation">Welcome To,<br/>{{ Auth::User()->name }}</p>
        </div>
    </div>
    <ul class="app-menu ">
        <li onclick="deletePost"><a class="app-menu__item {{Request ::is('home') ? ' active ' : ''}}" href="{!! URL::to('/home') !!}" ><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
        @php
            //if(Auth::User()->getRoleNames()[0] == "Admin"){
            //if(Auth::User()->role=='1'){
        @endphp
        <li class="treeview{{Request::is('productCategories*')|| Request::is('productSubCategories*') || Request::is('productBrands') || Request::is('productUnits') || Request::is('products*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-product-hunt"></i><span class="app-menu__label">Products </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productCategories.index') }}"><i class="app-menu__icon fa fa-deviantart"></i><span class="app-menu__label">Product Category</span></a></li>
{{--                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productSubCategories.index') }}"><i class="app-menu__icon fa fa-codepen"></i><span class="app-menu__label">Product Sub Category</span></a></li>--}}
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productBrands.index') }}"><i class="app-menu__icon fa fa-bandcamp"></i><span class="app-menu__label">Product Brand</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productUnits.index') }}"><i class="app-menu__icon fa fa-bandcamp"></i><span class="app-menu__label">Product Unit</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('products.index') }}"><i class="app-menu__icon fa fa-dropbox"></i><span class="app-menu__label">Product</span></a></li>
            </ul>
        </li>
        <li class="treeview{{Request::is('party*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-folder-open"></i><span class="app-menu__label">Party </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('party.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">Party List</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('party.create') }}"><i class="app-menu__icon fa fa-circle-o"></i> <span class="app-menu__label">Party Create</span></a></li>
            </ul>
        </li>
        <li class="treeview{{Request::is('productPurchases*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-cart-plus"></i><span class="app-menu__label">Finish Goods Stock In </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPurchases.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">List</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPurchases.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Create</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPurchases.supplier.due') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">Supplier Due</span></a></li>

            </ul>
        </li>
        <li class="treeview{{Request::is('productPurchaseRawMaterials*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-shopping-basket"></i><span class="app-menu__label">Raw Materials Stock In </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPurchaseRawMaterials.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">List</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPurchaseRawMaterials.create') }}"><i class="app-menu__icon fa fa-circle-o"></i> <span class="app-menu__label">Create</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPurchases.supplier.due.rawMaterials') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">Supplier Due</span></a></li>
            </ul>
        </li>
        <li class="treeview{{Request::is('productPurchaseReturn*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-arrow-up"></i><span class="app-menu__label">Purchase Return</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('returnable.purchase.product') }}"><i class="app-menu__icon fa fa-circle-o"></i> <span class="app-menu__label">Create </span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPurchaseReturn.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">List</span></a></li>
                {{--                <li><a class="app-menu__item" href="{{ route('productSaleReturns.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale Create</span></a></li>--}}

            </ul>
        </li>
        <li class="treeview{{Request::is('productPurchaseReturn*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-arrow-up"></i><span class="app-menu__label">Purchase Replace</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPurchaseReplacement.create') }}"><i class="app-menu__icon fa fa-circle-o"></i> <span class="app-menu__label">Create </span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPurchaseReplacement.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">List</span></a></li>
                {{--                <li><a class="app-menu__item" href="{{ route('productSaleReturns.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale Create</span></a></li>--}}

            </ul>
        </li>
        <li class="treeview{{Request::is('productProductions*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-thumb-tack"></i><span class="app-menu__label">Production(Raw Materials) </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productProductions.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">List</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productProductions.create') }}"><i class="app-menu__icon fa fa-circle-o"></i> <span class="app-menu__label">Create</span></a></li>
            </ul>
        </li>
        <li class="treeview{{Request::is('productPosSales*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-sign-out"></i><span class="app-menu__label">POS Sale(FG) </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPosSales.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">List</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productPosSales.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Create</span></a></li>
            </ul>
        </li>
        <li class="treeview{{Request::is('productSales*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-arrow-down"></i><span class="app-menu__label">Whole Sale(FG) </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productSales.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">List</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productSales.create') }}"><i class="app-menu__icon fa fa-circle-o"></i> <span class="app-menu__label">Create</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productSales.customer.due') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">Customer Due</span></a></li>
            </ul>
        </li>
{{--        <li class="treeview{{Request::is('productSales*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Product Sale </span><i class="treeview-indicator fa fa-angle-right"></i></a>--}}
{{--            <ul class="treeview-menu">--}}
{{--                <li><a class="app-menu__item" href="{{ route('productSales.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale List</span></a></li>--}}
{{--                <li><a class="app-menu__item" href="{{ route('productSales.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale Create</span></a></li>--}}
{{--            </ul>--}}
{{--        </li>--}}
        <li class="treeview{{Request::is('productSaleReturns*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-arrow-up"></i><span class="app-menu__label">Sale Return</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('returnable.sale.product') }}"><i class="app-menu__icon fa fa-circle-o"></i> <span class="app-menu__label">Create </span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productSaleReturns.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">List</span></a></li>
                {{--                <li><a class="app-menu__item" href="{{ route('productSaleReturns.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale Create</span></a></li>--}}

            </ul>
        </li>
        <li class="treeview{{Request::is('productSaleReplacement*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-arrow-up"></i><span class="app-menu__label">Sale Replace</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productSaleReplacement.create') }}"><i class="app-menu__icon fa fa-circle-o"></i> <span class="app-menu__label">Create </span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('productSaleReplacement.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">List</span></a></li>
                {{--                <li><a class="app-menu__item" href="{{ route('productSaleReturns.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale Create</span></a></li>--}}

            </ul>
        </li>
        <li class="treeview{{Request::is('stock*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-sort-amount-asc"></i><span class="app-menu__label">Stock </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('stock.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">Stock List</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('stock.summary.list') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">Stock Summary</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('stock.low.list') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">Stock Low</span></a></li>
            </ul>
        </li>
        <li class="treeview{{Request::is('officeCostingCategory*') || Request::is('expenses*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-toggle-down"></i><span class="app-menu__label">Office Costing </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('officeCostingCategory.index') }}"><i class="app-menu__icon fa fa-circle-o"></i><span class="app-menu__label">Office Costing Category</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('expenses.index') }}"><i class="app-menu__icon fa fa-circle"></i><span class="app-menu__label">Expense</span></a></li>
            </ul>
        </li>
        <li class="treeview{{Request::is('transaction*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-signal"></i><span class="app-menu__label">Transaction </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('transaction.index') }}"><i class="app-menu__icon fa fa-circle"></i> <span class="app-menu__label">Transaction List</span></a></li>
                {{--                <li class="custom_li_bg"><a class="app-menu__item" href="{{ route('delivery.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Delivery Charge List</span></a></li>--}}
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('transaction.partyDiscount') }}"><i class="app-menu__icon fas fa fa-circle"></i> <span class="app-menu__label">Party Discount</span></a></li>
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('transaction.lossProfit') }}"><i class="app-menu__icon fas fa fa-circle"></i> <span class="app-menu__label">Loss/Profit</span></a></li>
            </ul>
        </li>
        <hr/>
        <li><a class="app-menu__item" href="{{ route('stores.index') }}"><i class="app-menu__icon fa fa-window-restore"></i><span class="app-menu__label">Stores</span></a></li>
        <li><a class="app-menu__item" {{Request ::is('/users')  ? ' active ' : ''}} href="{{ route('users.index') }}"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">Users</span></a></li>
        <li class="treeview{{Request::is('roles*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-circle"></i><span class="app-menu__label">Role Permissions </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li class="custom_li_bg"><a class="app-menu__item custom_li_a_position" href="{{ route('roles.index') }}"><i class="app-menu__icon fa fa-circle"></i><span class="app-menu__label">Role Permission List</span></a></li>
                {{--                <li class="custom_li_bg"><a class="app-menu__item" href="{{ route('roles.create') }}"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">Create Role Permission</span></a></li>--}}
            </ul>
        </li>
        @php
            //}
        @endphp
    </ul>
</aside>
