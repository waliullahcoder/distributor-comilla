<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AboutController,
    AdditionalCostController,
    UserController,
    RoleController,
    PageController,
    MenuController,
    AreaController,
    SalesController,
    GroupController,
    OrderController,
    StoreController,
    StaffController,
    AdminController,
    BranchController,
    ReportController,
    VendorController,
    InvestController,
    RegionController,
    SliderController,
    ClientController,
    VehicleController,
    CompanyController,
    SectionController,
    PaymentController,
    LiftingController,
    ContactController,
    ProductController,
    SettingController,
    PreOrderController,
    PosSalesController,
    TransferController,
    MenuItemController,
    CoaSetupController,
    DeliveryController,
    InvestorController,
    LocationController,
    CustomerController,
    CampaignController,
    CategoryController,
    SiteItemsController,
    TerritoryController,
    AdminMenuController,
    AttributeController,
    SocialWorkController,
    CollectionController,
    OrderAssignController,
    SalesReturnController,
    TestimonialController,
    OnlineOrderController,
    OrderStatusController,
    OrderReturnController,
    CampaignFaqController,
    BulkForwardController,
    ClientPriceController,
    InvestRenewController,
    DetailsCardController,
    DeliveryManController,
    ChainClientController,
    ShowcaseItemController,
    OfflineOrderController,
    AdminSettingController,
    RunningSalesController,
    SubscriptionController,
    OrderPackageController,
    ClientMessageController,
    DeliveryAgentController,
    ModeratorTeamController,
    VendorPaymentController,
    VoucherRejectController,
    CourierAssignController,
    LiftingReturnController,
    VoucherApproveController,
    DeliveryChargeController,
    OrderDashboardController,
    CampaignReviewController,
    BulkCollectionController,
    ClientCategoryController,
    AttributeValueController,
    AdminMenuActionController,
    CampaignSectionController,
    TransferReceiveController,
    GroupSaleTargetController,
    InvestorPaymentController,
    OrderCollectionController,
    ModeratorPaymentController,
    SpecialFoodItemsController,
    CampaignFacilityController,
    AutomationRejectController,
    AutomationApproveController,
    DebitVoucherEntryController,
    ProfitDistributionController,
    CreditVoucherEntryController,
    InvestorSattlementController,
    SalesReturnApproveController,
    OnlineOrderDeliveryController,
    JournalVoucherEntryController,
    DeliveryAgentPackageController,
    LiftingFashionProductController,
    LifestyleProductSalesController,
    LifestyleProductReturnController,
};

Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('login.index');
    Route::post('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/sidebar', [AdminController::class, 'sidebar'])->name('sidebar');
});

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['admin_permission']], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'edit'])->name('profile.index');
    Route::put('/change-images', [AdminController::class, 'changeImages'])->name('change-images');
    Route::put('/change-password', [AdminController::class, 'changePassword'])->name('change-password');
    Route::put('/profile', [AdminController::class, 'update'])->name('profile.update');
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

    // User
    Route::resource('/user', UserController::class);
    Route::get('/user/{id}/password', [UserController::class, 'changePassword'])->name('user.password');
    Route::put('/user/password/{id}', [UserController::class, 'passwordUpdate'])->name('user.password-update');

    // Admin Menu
    Route::resource('/admin-menu', AdminMenuController::class);

    // Admin Menu Actions
    Route::get('/admin-menu-actions/{id}', [AdminMenuActionController::class, 'index'])->name('admin-menuAction.index');
    Route::get('/admin-menu-actions/{id}/create', [AdminMenuActionController::class, 'create'])->name('admin-menuAction.create');
    Route::post('/admin-menu-actions/{id}/store', [AdminMenuActionController::class, 'store'])->name('admin-menuAction.store');
    Route::get('/admin-menu-actions/{id}/edit', [AdminMenuActionController::class, 'edit'])->name('admin-menuAction.edit');
    Route::put('/admin-menu-actions/{id}', [AdminMenuActionController::class, 'update'])->name('admin-menuAction.update');
    Route::delete('/admin-menu-actions/{id}', [AdminMenuActionController::class, 'destroy'])->name('admin-menuAction.destroy');

    // Role
    Route::resource('/role', RoleController::class);

    // Permission
    Route::get('/permission/{id}', [RoleController::class, 'rolePermissionEdit'])->name('rolePermission.edit');
    Route::put('/permission/permissions-update/{id}', [RoleController::class, 'rolePermissionUpdate'])->name('rolePermission.update');

    // Admin Settings
    Route::resource('/admin-settings', AdminSettingController::class);

    // Company Setup
    Route::resource('/company', CompanyController::class);

    // Vehicle Setup
    Route::resource('/vehicle', VehicleController::class);

    // Branch Setup
    Route::resource('/branch', BranchController::class);

    // Store Setup
    Route::resource('/store', StoreController::class);

    // Staff Setup
    Route::resource('/staff', StaffController::class);

    // Region Setup
    Route::resource('/region', RegionController::class);

    // Area Setup
    Route::resource('/area', AreaController::class);

    // Territory Setup
    Route::resource('/territory', TerritoryController::class);

    // Client Category Setup
    Route::resource('/client-category', ClientCategoryController::class);

    // Chain Client Setup
    Route::resource('/chain-client', ChainClientController::class);

    // Vendor Setup
    Route::resource('/vendor', VendorController::class);

    // Vendor Payment
    Route::resource('/vendor-payment', VendorPaymentController::class);

    // Client Setup
    Route::resource('/client', ClientController::class);

    // Sales Group Setup
    Route::resource('/group', GroupController::class);

    // Group Sales Target Setup
    Route::resource('/group-target', GroupSaleTargetController::class);

    // Client Price Setup
    Route::resource('/client-price', ClientPriceController::class);

    // Lifting Product
    Route::resource('/lifting', LiftingController::class);
    Route::get('/lifting-document/{id}', [LiftingController::class, 'showDocument'])->name('lifting-document.show');

    // Lifting Fashion Product
    Route::resource('/lifting-fashion-product', LiftingFashionProductController::class);

    // Lifting Product Return
    Route::resource('/lifting-return', LiftingReturnController::class);

    // Offline Order
    Route::resource('/offline-order', OfflineOrderController::class);

    // Sales
    Route::resource('/sales', SalesController::class);
    Route::get('/sales-invoice/{id}', [SalesController::class, 'invoice'])->name('sales.invoice');
    Route::get('/sales-vat/{id}', [SalesController::class, 'vat'])->name('sales.vat');
    Route::get('/search-edit', [SalesController::class, 'searchEdit'])->name('sales.search-edit');
    Route::resource('/pos-sales', PosSalesController::class);

    // Running Sales
    Route::resource('/running-sales', RunningSalesController::class);
    Route::get('/running-sales-search-edit', [RunningSalesController::class, 'searchEdit'])->name('running-sales.search-edit');

    // Lifestyle Product Sales
    Route::resource('/sales-lifestyle-product', LifestyleProductSalesController::class);
    Route::get('/sales-lifestyle-product-invoice/{id}', [LifestyleProductSalesController::class, 'invoice'])->name('sales-lifestyle-product.invoice');
    Route::get('/sales-lifestyle-product-vat/{id}', [LifestyleProductSalesController::class, 'vat'])->name('sales-lifestyle-product.vat');
    Route::get('/sales-lifestyle-product-edit', [LifestyleProductSalesController::class, 'searchEdit'])->name('sales-lifestyle-product.search-edit');

    // Sales Return
    Route::resource('/sales-return', SalesReturnController::class);

    // Return Receive
    Route::resource('/return-approve', SalesReturnApproveController::class);

    // Lifestyle Product Return
    Route::resource('/lifestyle-product-return', LifestyleProductReturnController::class);

    // Collection
    Route::resource('/collection', CollectionController::class);

    // Bulk Collection
    Route::resource('/bulk-collection', BulkCollectionController::class);

    // Delivery
    Route::resource('/delivery', DeliveryController::class);
    Route::get('/delivery-gatepass/{id}', [DeliveryController::class, 'gatePass'])->name('delivery.gatepass');
    Route::get('/delivery-delivered/{id}', [DeliveryController::class, 'delivered'])->name('delivery.delivered');

    // Transfer Product
    Route::resource('/transfer', TransferController::class);

    // Transfer Product Receive
    Route::resource('/transfer-receive', TransferReceiveController::class);

    // Category
    Route::resource('/category', CategoryController::class);

    // Attribute
    Route::resource('/attribute', AttributeController::class);

    // Attribute Value
    Route::get('/attribute-values/{id}', [AttributeValueController::class, 'index'])->name('attribute-value.index');
    Route::post('/attribute-values/{id}', [AttributeValueController::class, 'store'])->name('attribute-value.store');
    Route::get('/attribute-values/{id}/edit', [AttributeValueController::class, 'edit'])->name('attribute-value.edit');
    Route::put('/attribute-values/{id}/update', [AttributeValueController::class, 'update'])->name('attribute-value.update');
    Route::delete('/attribute-values/{id}/delete', [AttributeValueController::class, 'destroy'])->name('attribute-value.destroy');

    // Product
    Route::resource('/product', ProductController::class);
    Route::get('/product-attributes/{id}', [ProductController::class, 'attributes'])->name('product.attributes');
    Route::put('/product-attributes/{id}', [ProductController::class, 'attributesUpdate'])->name('product.attributes.update');

    // Location
    Route::resource('/location', LocationController::class);

    // Customers
    Route::resource('/customers', CustomerController::class);

    // Order
    Route::resource('/online-order', OrderController::class);
    Route::get('/export-process-order', [OrderController::class, 'exportProcess'])->name('process-order.export');

    // Order
    Route::get('/cancel-orders', [OrderController::class, 'CancelOrder'])->name('cancel-order.index');
    Route::get('/cancel-order/{id}/approve', [OrderController::class, 'approveCancelOrder'])->name('cancel-order.approve');

    // Delivery Charge Setup
    Route::resource('/delivery-charge', DeliveryChargeController::class);

    // Online Order Delivery
    Route::resource('/online-order-delivery', OnlineOrderDeliveryController::class);
    Route::get('/online-order-delivery-gatepass/{id}', [OnlineOrderDeliveryController::class, 'gatePass'])->name('online-order-delivery.gatepass');

    // Online Order Dashboard
    Route::resource('/order-dashboard', OrderDashboardController::class);

    // Accounting
    Route::resource('/coa-setup', CoaSetupController::class);

    // Accounts Transaction
    Route::resource('/debit-voucher-entry', DebitVoucherEntryController::class);
    Route::get('/debit-voucher-entry/{id}/print', [DebitVoucherEntryController::class, 'print'])->name('debit-voucher-entry.print');
    Route::resource('/credit-voucher-entry', CreditVoucherEntryController::class);
    Route::get('/credit-voucher-entry/{id}/print', [creditVoucherEntryController::class, 'print'])->name('credit-voucher-entry.print');
    Route::resource('/journal-voucher-entry', JournalVoucherEntryController::class);
    Route::get('/journal-voucher-entry/{id}/print', [journalVoucherEntryController::class, 'print'])->name('journal-voucher-entry.print');
    Route::resource('/voucher-approve', VoucherApproveController::class);
    Route::resource('/voucher-reject', VoucherRejectController::class);
    Route::resource('/automation-approve', AutomationApproveController::class);
    Route::resource('/automation-reject', AutomationRejectController::class);

    // Investor
    Route::resource('/investor', InvestorController::class);

    // Invest
    Route::resource('/invest', InvestController::class);

    // Invest Renew
    Route::resource('/invest-renew', InvestRenewController::class);

    // Profit Distribute
    Route::resource('/profit-distribute', ProfitDistributionController::class);
    Route::get('/profit-distribute/{id}/print', [ProfitDistributionController::class, 'print'])->name('profit-distribute.print');

    // Investor Payment
    Route::resource('/investor-payment', InvestorPaymentController::class);

    // Investor Payment
    Route::resource('/payment', PaymentController::class);

    // Investor Sattlement
    Route::resource('/investor-sattlement', InvestorSattlementController::class);

    // Order
    Route::resource('/order', OnlineOrderController::class);

    // Order Return
    Route::resource('/order-return', OrderReturnController::class);

    // Delivery Man
    Route::resource('/delivery-man', DeliveryManController::class);

    // Bulk Forward
    Route::resource('/bulk-forward', BulkForwardController::class);

    // Assign Order
    Route::resource('/order-assign', OrderAssignController::class);

    // Order Status
    Route::resource('/order-status', OrderStatusController::class);

    // Order Collection
    Route::resource('/order-collection', OrderCollectionController::class);

    // Courier Assign
    Route::resource('/courier-assign', CourierAssignController::class);

    // Order Package
    Route::resource('/order-package', OrderPackageController::class);

    // Campaign Setup
    Route::resource('/campaign', CampaignController::class);

    // Campaign Review
    Route::get('/campaign-review/{id}', [CampaignReviewController::class, 'index'])->name('campaign-review.index');
    Route::get('/campaign-review/{id}/create', [CampaignReviewController::class, 'create'])->name('campaign-review.create');
    Route::post('/campaign-review/{id}/store', [CampaignReviewController::class, 'store'])->name('campaign-review.store');
    Route::get('/campaign-review/{id}/edit', [CampaignReviewController::class, 'edit'])->name('campaign-review.edit');
    Route::put('/campaign-review/{id}', [CampaignReviewController::class, 'update'])->name('campaign-review.update');
    Route::delete('/campaign-review/{id}', [CampaignReviewController::class, 'destroy'])->name('campaign-review.destroy');

    // Campaign Faqs
    Route::get('/campaign-faq/{id}', [CampaignFaqController::class, 'index'])->name('campaign-faq.index');
    Route::get('/campaign-faq/{id}/create', [CampaignFaqController::class, 'create'])->name('campaign-faq.create');
    Route::post('/campaign-faq/{id}/store', [CampaignFaqController::class, 'store'])->name('campaign-faq.store');
    Route::get('/campaign-faq/{id}/edit', [CampaignFaqController::class, 'edit'])->name('campaign-faq.edit');
    Route::put('/campaign-faq/{id}', [CampaignFaqController::class, 'update'])->name('campaign-faq.update');
    Route::delete('/campaign-faq/{id}', [CampaignFaqController::class, 'destroy'])->name('campaign-faq.destroy');

    // Campaign Facilities
    Route::get('/campaign-facility/{id}', [CampaignFacilityController::class, 'index'])->name('campaign-facility.index');
    Route::get('/campaign-facility/{id}/create', [CampaignFacilityController::class, 'create'])->name('campaign-facility.create');
    Route::post('/campaign-facility/{id}/store', [CampaignFacilityController::class, 'store'])->name('campaign-facility.store');
    Route::get('/campaign-facility/{id}/edit', [CampaignFacilityController::class, 'edit'])->name('campaign-facility.edit');
    Route::put('/campaign-facility/{id}', [CampaignFacilityController::class, 'update'])->name('campaign-facility.update');
    Route::delete('/campaign-facility/{id}', [CampaignFacilityController::class, 'destroy'])->name('campaign-facility.destroy');

    // Campaign Section
    Route::get('/campaign-section/{id}', [CampaignSectionController::class, 'index'])->name('campaign-section.index');
    Route::get('/campaign-section/{id}/create', [CampaignSectionController::class, 'create'])->name('campaign-section.create');
    Route::post('/campaign-section/{id}/store', [CampaignSectionController::class, 'store'])->name('campaign-section.store');
    Route::get('/campaign-section/{id}/edit', [CampaignSectionController::class, 'edit'])->name('campaign-section.edit');
    Route::put('/campaign-section/{id}', [CampaignSectionController::class, 'update'])->name('campaign-section.update');
    Route::delete('/campaign-section/{id}', [CampaignSectionController::class, 'destroy'])->name('campaign-section.destroy');

    // Pre Order
    Route::resource('/pre-order', PreOrderController::class);
    Route::match(['post', 'PUT'], '/pre-order-section/{id}', [PreOrderController::class, 'storeUpdate'])->name('pre-order-section.store-update');
    Route::get('/pre-order-section/{id}/edit', [PreOrderController::class, 'editSection'])->name('pre-order-section.edit');
    Route::delete('/pre-order-section/{id}', [PreOrderController::class, 'destroySection'])->name('pre-order-section.destroy');

    // Deliver Agent
    Route::resource('/delivery-agent', DeliveryAgentController::class);

    // Delivery Agent Package
    Route::resource('/agent-package', DeliveryAgentPackageController::class);

    // Additional Cost
    Route::resource('/additional-cost', AdditionalCostController::class);

    // Moderator Team
    Route::resource('/moderator-team', ModeratorTeamController::class);

    // Moderator Payment
    Route::resource('/moderator-payment', ModeratorPaymentController::class);
    Route::get('/moderator-payment/{id}/print', [ModeratorPaymentController::class, 'print'])->name('moderator-payment.print');
});

// ================== Reports ================== //
Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['admin_permission']], function () {
    Route::get('/product-list', [ReportController::class, 'productList'])->name('product-list.index');
    Route::get('/lifting-report', [ReportController::class, 'liftingReport'])->name('lifting-history.index');
    Route::get('/lifting-return-history', [ReportController::class, 'liftingReturnHistory'])->name('lifting-return-history.index');
    Route::get('/vendor-payment-history', [ReportController::class, 'vendorPayment'])->name('vendor-payment-history.index');
    Route::get('/vendor-statement', [ReportController::class, 'vendorStatement'])->name('vendor-statement.index');
    Route::get('/client-list', [ReportController::class, 'clientList'])->name('client-list.index');
    Route::get('/sales-history', [ReportController::class, 'salesHistroy'])->name('sales-history.index');
    Route::get('/collection-history', [ReportController::class, 'collectionHistory'])->name('collection-history.index');
    Route::get('/return-history', [ReportController::class, 'returnHistory'])->name('return-history.index');
    Route::get('/client-statement', [ReportController::class, 'clientStatement'])->name('client-statement.index');
    Route::get('/client-delivery-report', [ReportController::class, 'clientDeliveryReport'])->name('client.delivery_report.index');
    Route::get('/delivery-statement', [ReportController::class, 'deliveryStatement'])->name('delivery-statement.index');
    Route::get('/transfer-log', [ReportController::class, 'transferLog'])->name('transfer-log.index');
    Route::get('/stock-status', [ReportController::class, 'stockStatus'])->name('stock-status.index');
    Route::get('/stock-valuation', [ReportController::class, 'stockValuation'])->name('stock-valuation.index');
    Route::get('/sales-contribution', [ReportController::class, 'salesContribution'])->name('sales-contribution.index');
    Route::get('/sales-realization', [ReportController::class, 'salesRealization'])->name('sales-realization.index');
    Route::get('/lifting-realization', [ReportController::class, 'liftingRealization'])->name('lifting-realization.index');
    Route::get('/sales-ageing', [ReportController::class, 'salesAgeing'])->name('sales-ageing.index');
    Route::get('/client-outstanding', [ReportController::class, 'clientOutstanding'])->name('client-outstanding.index');
    Route::get('/sales-target-achivement', [ReportController::class, 'SalesTargetAchievement'])->name('sales-target-achivement.index');
    Route::get('/product-wise-profit', [ReportController::class, 'productWiseProfit'])->name('product-wise-profit.index');
    Route::get('/access-log', [ReportController::class, 'accessLog'])->name('access-log.index');
    Route::get('/product-statement', [ReportController::class, 'productStatement'])->name('product-statement.index');
    Route::get('/salesman-flowchart', [ReportController::class, 'salesManFlowChart'])->name('salesman-flowchart.index');
    Route::get('/client-sales-flow', [ReportController::class, 'clientSalesFlow'])->name('client-sales-flow.index');
    Route::get('/online-sales-history', [ReportController::class, 'onlineSalesHistory'])->name('online-sales-history.index');
    Route::get('/coa-list', [ReportController::class, 'coaList'])->name('coa-list.index');
    Route::get('/voucher-list', [ReportController::class, 'voucherList'])->name('voucher-list.index');
    Route::get('/cash-book', [ReportController::class, 'cashBook'])->name('cash-book.index');
    Route::get('/bank-book', [ReportController::class, 'bankBook'])->name('bank-book.index');
    Route::get('/transaction-ledger', [ReportController::class, 'transactionLedger'])->name('transaction-ledger.index');
    Route::get('/cash-flow-statement', [ReportController::class, 'cashFlowStatement'])->name('cash-flow-statement.index');
    Route::get('/general-ledger', [ReportController::class, 'generalLedger'])->name('general-ledger.index');
    Route::get('/income-statement', [ReportController::class, 'incomeStatement'])->name('income-statement.index');
    Route::get('/trial-balance', [ReportController::class, 'trialBalance'])->name('trial-balance.index');
    Route::get('/balance-sheet', [ReportController::class, 'balanceSheet'])->name('balance-sheet.index');
    Route::get('/head-details', [ReportController::class, 'headDetails'])->name('head-details.index');
    Route::get('/receive-payment', [ReportController::class, 'receivePayment'])->name('receive-payment.index');
    Route::get('/receive-payment-head-details', [ReportController::class, 'receivePaymentHeadDetails'])->name('receive-payment-head-details.index');
    Route::match(['get', 'post'], '/generate-barcode', [ReportController::class, 'generateBarcode'])->name('generate-barcode.index');
    Route::get('/profit-loss', [ReportController::class, 'profitLossReport'])->name('profit-loss.index');
    Route::get('/profit-sheet', [ReportController::class, 'profitSheet'])->name('profit-sheet.index');
    Route::get('/investor-statement', [ReportController::class, 'investorStatement'])->name('investor-statement.index');
    Route::get('/profit-due-list', [ReportController::class, 'profitDueList'])->name('profit-due-list.index');
    Route::get('/order-list', [ReportController::class, 'orderList'])->name('order-list.index');
    Route::get('/required-product', [ReportController::class, 'requiredProduct'])->name('required-product.index');
    Route::get('/areawise-orders', [ReportController::class, 'areawiseOrders'])->name('areawise-orders.index');
    Route::get('/areawise-order', [ReportController::class, 'areawiseOrder'])->name('areawise-order.index');
    Route::get('/product-wise-sales-summary', [ReportController::class, 'productWiseSalesSummary'])->name('product-wise-sales-summary.index');
    Route::get('/area-wise-sales-statement', [ReportController::class, 'areaWiseSalesStatement'])->name('area-wise-sales-statement.index');
    Route::get('/store-wise-sales-statement', [ReportController::class, 'storeWiseSalesStatement'])->name('store-wise-sales-statement.index');
    Route::get('/monthly-statement', [ReportController::class, 'monthlyStatement'])->name('monthly-statement.index');
    Route::get('/storewise-orders', [ReportController::class, 'storewiseOrders'])->name('storewise-orders.index');
    Route::get('/storewise-order', [ReportController::class, 'storewiseOrder'])->name('storewise-order.index');
    Route::get('/on-route-orders', [ReportController::class, 'onRouteOrders'])->name('on-route-orders.index');
    Route::get('/deliveryman-delivery-statement', [ReportController::class, 'deliverymanDeliveryStatement'])->name('deliveryman-delivery-statement.index');
    Route::get('/customer-list', [ReportController::class, 'customerList'])->name('customer-list.index');
    Route::get('/monthly-summary-sheet', [ReportController::class, 'monthlySummarySheet'])->name('monthly-summary-sheet.index');
    Route::get('/monthly-chart', [ReportController::class, 'monthlyChart'])->name('monthly-chart.index');
    Route::get('/collection-report', [ReportController::class, 'collectionReport'])->name('collection-report.index');
    Route::get('/deliveryman-orders', [ReportController::class, 'deliverymanOrders'])->name('deliveryman-orders.index');
    Route::get('/courier-report', [ReportController::class, 'courierReport'])->name('courier-report.index');
    Route::get('/moderator-orders', [ReportController::class, 'moderatorOrders'])->name('moderator-orders.index');
});
// ================== Reports ================== //

// Frontend CMS
Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['admin_permission']], function () {

    // Settings
    Route::resource('/settings', SettingController::class);

    // Page
    Route::resource('/page', PageController::class);

    // Home Page Slider
    Route::resource('/slider', SliderController::class);

    // Site Items
    Route::resource('/site-item', SiteItemsController::class);

    // Special Food Items
    Route::resource('/special-food-item', SpecialFoodItemsController::class);

    // Details Card Items
    Route::resource('/details-card', DetailsCardController::class);

    // Details Showcase Items
    Route::resource('/showcase-item', ShowcaseItemController::class);

    // Site Contact Details
    Route::resource('/testimonial', TestimonialController::class);

    // Site About Us Details
    Route::resource('/about', AboutController::class);

    // Site About Us Details
    Route::resource('/social-working', SocialWorkController::class);

    // Site Contact Details
    Route::resource('/contact', ContactController::class);

    // Subscription
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');

    // Client Message
    Route::get('/client-message', [ClientMessageController::class, 'index'])->name('client-message.index');
    Route::get('/client-message/{id}', [ClientMessageController::class, 'show'])->name('client-message.show');

    // Online Requst
    Route::get('/client-request', [ClientMessageController::class, 'onlineRequest'])->name('client-request.index');
    Route::get('/client-request/{id}', [ClientMessageController::class, 'showMessage'])->name('client-request.show');

    // Frontend Menu
    Route::resource('/menu', MenuController::class);
    Route::get('/menu-item/{id}', [MenuItemController::class, 'index'])->name('menu-item.index');
    Route::put('/menu-item/{id}', [MenuItemController::class, 'update'])->name('menu-item.update');
    Route::get('/menu-serialize/{id}', [MenuItemController::class, 'edit'])->name('menu-item.serialize');
    Route::delete('/menu-item/{id}', [MenuItemController::class, 'destroy'])->name('menu-item.destroy');

    // Homepage Sections
    Route::resource('/sections', SectionController::class);
});
