

function showProductItems() {
    $.ajax({
        url: "./adminView/viewAllProducts.php",
        method: "post",
        data: {record: 1},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}
function showCategory() {
    $.ajax({
        url: "./adminView/viewCategories.php",
        method: "post",
        data: {record: 1},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}
function showMaterial() {
    $.ajax({
        url: "./adminView/viewMaterial.php",
        method: "post",
        data: {record: 1},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}
function showvendor() {
    $.ajax({
        url: "./adminView/viewvendor.php",
        method: "post",
        data: {record: 1},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}
function showsubscription() {
    $.ajax({
        url: "./adminView/viewsubscription.php",
        method: "post",
        data: {record: 1},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}
function showsubcategory() {
    $.ajax({
        url: "./adminView/viewsubcategory.php",
        method: "post",
        data: {record: 1},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}

function showCustomers() {
    $.ajax({
        url: "./adminView/viewCustomers.php",
        method: "post",
        data: {record: 1},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}

function showOrders() {
    $.ajax({
        url: "./adminView/viewAllOrders.php",
        method: "post",
        data: {record: 1},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}
function showadmin() {
    $.ajax({
        url: "./adminView/viewadmin.php",
        method: "post",
        data: {record: 1},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}

function ChangeOrderStatus(id) {
    $.ajax({
        url: "./controller/updateOrderStatus.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            alert('Order Status updated successfully');
            $('form').trigger('reset');
            showOrders();
        }
    });
}

function ChangePay(id) {
    $.ajax({
        url: "./controller/updatePayStatus.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            alert('Payment Status updated successfully');
            $('form').trigger('reset');
            showOrders();
        }
    });
}


//add product data
function addItems() {
    var p_name = $('#p_name').val();
    var p_desc = $('#p_desc').val();
    var p_price = $('#p_price').val();
    var category = $('#category').val();
    var sub_category = $('#sub_category').val();
    var height = $('#height').val();
    var width = $('#width').val();
    var length = $('#length').val();
    var quantity = $('#quantity').val();
    var stock_status = $('#stock_status').val();

    var upload = $('#upload').val();
    var file = $('#file')[0].files[0];

    var fd = new FormData();
    fd.append('p_name', p_name);
    fd.append('p_desc', p_desc);
    fd.append('p_price', p_price);
    fd.append('category', category);
    fd.append('sub_category', sub_category);
    fd.append('height', height);
    fd.append('width', width);
    fd.append('length', length);
    fd.append('quantity', quantity);
    fd.append('stock_status', stock_status);
    fd.append('file', file);
    fd.append('upload', upload);
    $.ajax({
        url: "./controller/addItemController.php",
        method: "post",
        data: fd,
        processData: false,
        contentType: false,
        success: function (data) {
            alert('Product Added successfully.');
            $('form').trigger('reset');
            showProductItems();
        }
    });
}

//edit product data
//function itemEditForm(id) {
//    $.ajax({
//        url: "./adminView/editItemForm.php",
//        method: "post",
//        data: {record: id},
//        success: function (data) {
//            $('.allContent-section').html(data);
//        }
//    });
//}
function categoryEdit(id) {
    $.ajax({
        url: "./adminView/catEditController.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}
function MaterialEdit(id) {
    $.ajax({
        url: "./adminView/matEditController.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}
function subscripedit(id) {
    $.ajax({
        url: "./adminView/subscripedit.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}

function AdminEdit(id) {
    $.ajax({
        url: "./adminView/adminEditController.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}
function updateadmin(){
    var admin_id = $('#admin_id').val();
    var admin_name = $('#admin_name').val();
    
    var fd = new FormData();
    fd.append('admin_id', admin_id);
    fd.append('admin_name', admin_name);
    
    $.ajax({
        url: './controller/updateadmincontroller.php',
        method: 'post',
        data: fd,
        processData: false,
        contentType: false,
        success: function (data) {
            alert('admin Updated Successfully.');
            $('form').trigger('reset');
            showadmin();
        }
    });
}
function updatecat(){
    var category_id = $('#category_id').val();
    var c_name = $('#c_name').val();
    
    var fd = new FormData();
    fd.append('category_id', category_id);
    fd.append('c_name', c_name);
    
    $.ajax({
        url: './controller/updatecatController.php',
        method: 'post',
        data: fd,
        processData: false,
        contentType: false,
        success: function (data) {
            alert('category Update Success.');
            $('form').trigger('reset');
            showCategory();
        }
    });
}
function updatemat(){
    var material_id = $('#material_id').val();
    var m_name = $('#m_name').val();
    
    var fd = new FormData();
    fd.append('material_id', material_id);
    fd.append('m_name', m_name);
    
    $.ajax({
        url: './controller/updatematController.php',
        method: 'post',
        data: fd,
        processData: false,
        contentType: false,
        success: function (data) {
            alert('material Update Success.');
            $('form').trigger('reset');
            showMaterial();
        }
    });
}
function updatesubscription(){
    var subscrip_id = $('#subscrip_id').val();
    var subscrip_period = $('#subscrip_period').val();
    var subscrip_amount = $('#subscrip_amount').val();
    
    var fd = new FormData();
    fd.append('subscrip_id', subscrip_id);
    fd.append('subscrip_period', subscrip_period);
    fd.append('subscrip_amount', subscrip_amount);
    
    $.ajax({
        url: './controller/updatesubscriptioncontroller.php',
        method: 'post',
        data: fd,
        processData: false,
        contentType: false,
        success: function (data) {
            alert('Subscription Update Success.');
            $('form').trigger('reset');
            showsubscription();
        }
    });
}

////update product after submit
//function updateItems() {
//    var product_id = $('#product_id').val();
//    var p_name = $('#p_name').val();
//    var p_desc = $('#p_desc').val();
//    var p_price = $('#p_price').val();
//    var category = $('#category').val();
//    var existingImage = $('#existingImage').val();
//    var newImage = $('#newImage')[0].files[0];
//    var fd = new FormData();
//    fd.append('product_id', product_id);
//    fd.append('p_name', p_name);
//    fd.append('p_desc', p_desc);
//    fd.append('p_price', p_price);
//    fd.append('category', category);
//    fd.append('existingImage', existingImage);
//    fd.append('newImage', newImage);
//
//    $.ajax({
//        url: './controller/updateItemController.php',
//        method: 'post',
//        data: fd,
//        processData: false,
//        contentType: false,
//        success: function (data) {
//            alert('Data Update Success.');
//            $('form').trigger('reset');
//            showProductItems();
//        }
//    });
//}

//delete product data
function itemDelete(id) {
    $.ajax({
        url: "./controller/deleteItemController.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            alert('Items Successfully deleted');
            $('form').trigger('reset');
            showProductItems();
        }
    });
}


//delete cart data
function cartDelete(id) {
    $.ajax({
        url: "./controller/deleteCartController.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            alert('Cart Item Successfully deleted');
            $('form').trigger('reset');
            showMyCart();
        }
    });
}


function eachDetailsForm(id) {
    $.ajax({
        url: "./view/viewEachDetails.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}



//delete category data
function categoryDelete(id) {
    $.ajax({
        url: "./controller/catDeleteController.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            alert('Category Successfully deleted');
            $('form').trigger('reset');
            showCategory();
        }
    });
}
function materialDelete(id) {
    $.ajax({
        url: "./controller/matDeleteController.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            alert('Material Successfully deleted');
            $('form').trigger('reset');
            showMaterial();
        }
    });
}
function adminDelete(id) {
    $.ajax({
        url: "./controller/AdminDeleteController.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            alert('admin Successfully deleted');
            $('form').trigger('reset');
            showadmin();
        }
    });
}

function sizeDelete(id) {
    $.ajax({
        url: "./controller/deleteSizeController.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            alert('Size Successfully deleted');
            $('form').trigger('reset');
            showSizes();
        }
    });
}

function subscripdelete(id){
    $.ajax({
        url: "./controller/subscriptiondelete.php",
        method: "post",
        data: {record: id},
        success: function(data){
            alert('Successfully deleted');
            $('form').trigger('reset');
            showsubscription();
        }
    });
}
function subcatDelete(id) {
    $.ajax({
        url: "./controller/deletesubcatController.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            alert('Successfully deleted');
            $('form').trigger('reset');
            showsubcategory();
        }
    });
}

function subcatEditForm(id) {
    $.ajax({
        url: "./adminView/editsubcatForm.php",
        method: "post",
        data: {record: id},
        success: function (data) {
            $('.allContent-section').html(data);
        }
    });
}


//update variation after submit
function updatesubcat() {
    var sub_id = $('#sub_id').val();
    var sub_name = $('#sub_name').val();
    
    var fd = new FormData();
    fd.append('sub_id', sub_id);
    fd.append('sub_name', sub_name);

    $.ajax({
        url: './controller/updatesubcatController.php',
        method: 'post',
        data: fd,
        processData: false,
        contentType: false,
        success: function (data) {
            alert('Update Success.');
            $('form').trigger('reset');
            showsubcategory();
        }
    });
}
//function search(id) {
//    $.ajax({
//        url: "./controller/searchController.php",
//        method: "post",
//        data: {record: id},
//        success: function (data) {
//            $('.eachCategoryProducts').html(data);
//        }
//    });
//}


//function quantityPlus(id) {
//    $.ajax({
//        url: "./controller/addQuantityController.php",
//        method: "post",
//        data: {record: id},
//        success: function (data) {
//            $('form').trigger('reset');
//            showMyCart();
//        }
//    });
//}
//function quantityMinus(id) {
//    $.ajax({
//        url: "./controller/subQuantityController.php",
//        method: "post",
//        data: {record: id},
//        success: function (data) {
//            $('form').trigger('reset');
//            showMyCart();
//        }
//    });
//}
//
//function checkout() {
//    $.ajax({
//        url: "./view/viewCheckout.php",
//        method: "post",
//        data: {record: 1},
//        success: function (data) {
//            $('.allContent-section').html(data);
//        }
//    });
//}


//function removeFromWish(id) {
//    $.ajax({
//        url: "./controller/removeFromWishlist.php",
//        method: "post",
//        data: {record: id},
//        success: function (data) {
//            alert('Removed from wishlist');
//        }
//    });
//}


//function addToWish(id) {
//    $.ajax({
//        url: "./controller/addToWishlist.php",
//        method: "post",
//        data: {record: id},
//        success: function (data) {
//            alert('Added to wishlist');
//        }
//    });
//}
