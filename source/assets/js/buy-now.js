let buyNowBtn = document.getElementById('buy-now-btn');
let offerPrice = document.getElementById('offer-form-price');
let biddersName = document.getElementById('offer-form-name');

let buyNowOnClick = function () {
    offerPrice.value = this.getAttribute('data-price');
    biddersName.focus();
};

buyNowBtn.addEventListener('click', buyNowOnClick, false);
