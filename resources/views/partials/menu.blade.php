<aside class="main-sidebar sidebar-dark-primary elevation-4" >
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>
    <?php  
        $user= Auth::user(); 
   		$order_url = ($user->user_type === 'supplier') ? route('supplier.orders.index') : route('admin.orders.index');
   ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route("admin.home") }}" class="nav-link">
                        <p><i class="fas fa-tachometer-alt"></i> <span>{{ trans('global.dashboard') }}</span></p>
                    </a>
                </li>
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is('admin/permissions*') ? 'menu-open' : '' }} {{ request()->is('admin/roles*') ? 'menu-open' : '' }} {{ request()->is('admin/users*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fas fa-users"></i>
                            <p><span>{{ trans('cruds.userManagement.title') }}</span><i class="right fa fa-angle-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                        <i class="fas fa-caret-right"></i>
                                        <p><span>{{ trans('cruds.permission.title') }}</span></p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                        <i class="fas fa-caret-right"></i>
                                        <p><span>{{ trans('cruds.role.title') }}</span></p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                        <i class="fas fa-caret-right"></i>
                                        <p><span>{{ trans('cruds.user.title') }}</span></p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('location_management_access')
                    <li class="nav-item has-treeview {{ request()->is('admin/states*') ? 'menu-open' : '' }} {{ request()->is('admin/city*') ? 'menu-open' : '' }} {{ request()->is('admin/transports*') ? 'menu-open' : '' }} {{ request()->is('admin/station*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fas fa-globe-asia"></i>
                            <p><span>{{ trans('cruds.LocationManagement.title') }}</span><i class="right fa fa-angle-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('state_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.states.index") }}" class="nav-link {{ request()->is('admin/states') || request()->is('admin/states/*') ? 'active' : '' }}">
                                        <i class="fas fa-caret-right"></i>
                                        <p><span>{{ trans('cruds.state.title') }}</span></p>
                                    </a>
                                </li>
                            @endcan
                            @can('city_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.city.index") }}" class="nav-link {{ request()->is('admin/city') || request()->is('admin/city/*') ? 'active' : '' }}">
                                        <i class="fas fa-caret-right"></i>
                                        <p><span>{{ trans('cruds.city.title') }}</span></p>
                                    </a>
                                </li>
                            @endcan
                           <!--  
                           @can('station_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.station.index") }}" class="nav-link {{ request()->is('admin/station') || request()->is('admin/station/*') ? 'active' : '' }}">
                                        <i class="fas fa-caret-right"></i>
                                        <p><span>{{ trans('cruds.station.title') }}</span></p>
                                    </a>
                                </li>
                            @endcan 
                            -->
                            @can('transport_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.transports.index") }}" class="nav-link {{ request()->is('admin/transports') || request()->is('admin/transports/*') ? 'active' : '' }}">
                                        <i class="fas fa-caret-right"></i>
                                        <p><span>{{ trans('cruds.transport.title') }}</span></p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('registration_management')
                    <li class="nav-item has-treeview {{ request()->is('admin/buyers*') ? 'menu-open' : '' }} {{ request()->is('admin/suppliers*') ? 'menu-open' : '' }} {{ request()->is('admin/brands*') ? 'menu-open' : '' }}  {{ request()->is('admin/item*') ? 'menu-open' : '' }} ">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fas fa-registered"></i>
                            <p><span>{{ trans('cruds.registration.title') }}</span><i class="right fa fa-angle-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('supplier_regs')
                                <li class="nav-item">
                                    <a href="{{ route("admin.suppliers.index") }}" class="nav-link {{ request()->is('admin/suppliers') || request()->is('admin/suppliers/*') ? 'active' : '' }}">
                                        <i class="fas fa-caret-right"></i>
                                        <p><span>{{ trans('cruds.supplier.title') }}</span></p>
                                    </a>
                                </li>
                            @endcan
                            @can('buyer_regs')
                                <li class="nav-item">
                                    <a href="{{ route("admin.buyers.index") }}" class="nav-link {{ request()->is('admin/buyers') || request()->is('admin/buyers/*') ? 'active' : '' }}">
                                        <i class="fas fa-caret-right"></i>
                                        <p><span>{{ trans('cruds.buyer.title') }}</span></p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('branches_management')
                    <li class="nav-item has-treeview {{ request()->is('admin/branches*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fas fa-h-square"></i>
                            <p><span> {{ trans('cruds.branch.title') }}</span><i class="right fa fa-angle-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('branchs')
                            <li class="nav-item">
                                <a href="{{ route("admin.branches.index") }}" class="nav-link {{ request()->is('admin/branches*') ? 'active' : '' }}">
                                    <i class="fas fa-caret-right"></i>
                                    <p><span>{{ trans('cruds.branch.title_singular') }}</span></p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('items_management')
                    <li class="nav-item has-treeview {{ request()->is('admin/sizes*') ? 'menu-open' : '' }} {{ request()->is('admin/colors*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fas fa-shopping-bag"></i>
                            <p><span>{{ trans('cruds.item.title') }}</span><i class="right fa fa-angle-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('size_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.sizes.index") }}" class="nav-link {{ request()->is('admin/sizes*') ? 'active' : '' }}">
                                    <i class="fas fa-caret-right"></i>
                                    <p><span>{{ trans('cruds.size.title') }}</span> </p>
                                </a>
                            </li>
                            @endcan
                            @can('color_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.colors.index") }}" class="nav-link {{ request()->is('admin/colors*') ? 'active' : '' }}">
                                    <i class="fas fa-caret-right"></i>
                                    <p><span>{{ trans('cruds.color.title') }}</span></p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('order_management')
                    <?php $roles = getRole(Auth::user()->id); ?>
                    @if($roles != trim('Branch Operator'))
                        @if($roles != trim('Head Office Operator'))
                        @if($roles != trim('Mix Branches'))
                        <li class="nav-item has-treeview {{ request()->is('admin/order*') ? 'menu-open' : '' }}">
                            <a class="nav-link nav-dropdown-toggle" href="#">
                                <i class="fas fa-clone"></i>
                                <p><span>{{ trans('cruds.order.title') }}</span><i class="right fa fa-angle-right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('order_access')
                                    <li class="nav-item">
                                        <a href="{{ route("admin.orders.index") }}" class="nav-link {{ request()->is('admin/order*') ? 'active' : '' }}">
                                            <i class="fas fa-caret-right"></i>
                                            <p><span>{{ trans('cruds.order.title') }}</span></p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
                        @endif
                    @endif
                @endcan
                <!--
                @can('supplier_accept_order')
                    <li class="nav-item">
                        <a href="{{ route("admin.accept_order",['supplier']) }}" class="nav-link {{ request()->is('admin/accept_order*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart"></i>
                            <p><span>{{ trans('cruds.order.accept_order_supplier') }}</span></p>
                        </a>
                    </li>
                    @endcan
                    @can('buyer_accept_order')
                    <li class="nav-item">
                        <a href="{{ route("admin.accept_order",['buyer'])  }}" class="nav-link {{ request()->is('admin/accept_order*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart"></i>
                            <p><span>{{ trans('cruds.order.accept_order_buyer') }}</span> </p>
                        </a>
                    </li>
                @endcan
                --> 
                @if(Auth::user()->user_type == 'supplier')   
                    <!--
                    <li class="nav-item has-treeview {{ request()->is('supplier/order*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fas fa-truck"></i>
                            <p><span>{{ trans('cruds.order.title') }}</span><i class="right fa fa-angle-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("supplier.orders.index") }}" class="nav-link {{ request()->is('supplier/order*') ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart"></i>
                                    <p><span>{{ trans('cruds.order.title') }}</span></p>
                                </a>
                            </li>
                            <?php 
                                /*  
                                    $user= Auth::user(); 
                                    $order_url = ($user->user_type == 'supplier') ? route('supplier.orders.index') : route('admin.orders.index');
                                */
                            ?>
                            <li class="nav-item">
                                <a href="{{$order_url}}?status=Rejected" class="nav-link {{ request()->is('supplier/order*') ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart"></i>
                                    <p>
                                        <span>Rejected Orders</span>
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    -->
                    <li class="nav-item">
                        <a href="{{route('supplier.enquiry.create')}}" class="nav-link" >
                            <p><i class="fa fa-key" aria-hidden="true"></i><span>Enquiry</span></p>
                        </a>
                    </li>              
                    <li class="nav-item">
                        <a href="{{ route('supplier.create_order')  }}" class="nav-link {{ request()->is('supplier/create_order*') ? 'active' : '' }}">
                            <i class="fas fa-dolly"></i> <p><span> Create Order</span></p>
                        </a>
                    </li>
                @endif
                @can('report_access')
                    <li class="nav-item has-treeview {{ request()->is('admin/report*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fas fa-copy"></i>
                            <p><span>Report</span><i class="right fa fa-angle-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('admin.report')}}" class="nav-link {{request()->is('admin/report') ? 'active' : '' }}">
                                    <p><i class="fas fa-caret-right"></i><span> Order Report</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.report.supplier')}}" class="nav-link {{ request()->is('admin/report/supplier') ? 'active' : '' }}">
                                    <p><i class="fas fa-caret-right"></i><span> Supplier Report</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.report.buyer-supplier')}}"  class="nav-link {{  request()->is('admin/report/buyer-supplier') ? 'active' : '' }}">
                                    <p><i class="fas fa-caret-right"></i><span> Buyer & Supplier profile</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.report.buyer')}}"  class="nav-link {{ request()->is('admin/report/buyer') ? 'active' : '' }}">
                                    <p><i class="fas fa-caret-right"></i><span> Buyer Report</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.report.item_brand')}}"  class="nav-link {{ request()->is('admin/report/item_brand') ? 'active' : '' }}">
                                    <p><i class="fas fa-caret-right"></i><span> Item, Brand Export</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.cancel-report')}}" class="nav-link {{request()->is('admin/cancel-report') ? 'active' : '' }}">
                                    <p><i class="fas fa-caret-right"></i><span> Cancel Order Report</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.last_six_month_order')}}" class="nav-link {{ request()->is('admin/last_six_month_order') ? 'active' : '' }}">
                                    <p><i class="fas fa-caret-right"></i><span> Last 6 Month Order History</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.report.apploginreport')}}" class="nav-link {{ request()->is('admin/report/apploginreport') ? 'active' : '' }}">
                                    <p><i class="fas fa-caret-right"></i><span> App Login Report</span></p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan       
                @can('create_order')                
                    <li class="nav-item">
                        <a href="{{ route("admin.create_order")  }}" class="nav-link {{ request()->is('admin/create_order*') ? 'active' : '' }}">
                            <i class="fas fa-dolly"></i> <p><span> Create Order</span></p>
                        </a>
                    </li>
                @endcan
                @can('site_information')
                    
                    
                    <li class="nav-item has-treeview {{ request()->is('admin/site-info*') ? 'menu-open' : '' }}">
                            <a class="nav-link nav-dropdown-toggle" href="#">
                                <i class="fas fa-clone"></i>
                                <p><span>Site Information</span><i class="right fa fa-angle-right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                 
                                    <li class="nav-item">
                                        <a href="{{ route("admin.site_info") }}" class="nav-link {{ request()->is('admin/site-info') ? 'active' : '' }}">
                                            <i class="fas fa-caret-right"></i>
                                            <p><span>Site Information</span></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route("admin.site_info.bank_details") }}" class="nav-link {{ request()->is('admin/site-info/bank-details') ? 'active' : '' }}">
                                            <i class="fas fa-caret-right"></i>
                                            <p><span>Bank Details</span></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route("admin.site_info.contact_details") }}" class="nav-link {{ request()->is('admin/site-info') ? 'active' : '' }}">
                                            <i class="fas fa-caret-right"></i>
                                            <p><span>Contact Info</span></p>
                                        </a>
                                    </li>
                            </ul>
                        </li>
                @endcan
                @can('enquiries')
                    <li class="nav-item">
                       <a href="{{route('admin.enquiries')}}" class="nav-link" >
                            <p><i class="fas fa-question"></i> <span> Enquiries</span></p>
                        </a>
                    </li>
                @endcan
                @can('reject_reason')
                <li class="nav-item">
                    <a href="{{route('admin.reject-reason.index')}}" class="nav-link" >
                        <p><i class="fa fa-lock" aria-hidden="true"></i><span> {{ trans('global.reason') }}</span></p>
                    </a>
                </li>
                @endcan
                @can('settings')
                <li class="nav-item">
                    <a href="{{url('admin/settings')}}" class="nav-link" >
                        <p><i class="fa fa-cogs" aria-hidden="true"></i><span> Settings </span></p>
                    </a>
                </li>
                @endcan
                <li class="nav-item">
                    <a href="{{route('change_password_form')}}" class="nav-link" >
                        <p><i class="fa fa-lock" aria-hidden="true"></i><span> {{ trans('global.change_password') }}</span></p>
                    </a>
                </li>
                 @if(Auth::user()->user_type == 'supplier')
                <li class="nav-item">
                    <a href="{{route('supplier.supplierlogout')}}" class="nav-link" >
                        <p><i class="fas fa-sign-out-alt"></i><span> {{ trans('global.logout') }}</span></p>
                    </a>
                </li>
                @endif
                 @if(Auth::user()->user_type != 'supplier')
                <li class="nav-item">
                    <a href="{{route('logout')}}" class="nav-link" >
                        <p><i class="fas fa-sign-out-alt"></i><span> {{ trans('global.logout') }}</span></p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>