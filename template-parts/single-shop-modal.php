<?php
/**
 * Shop modal for Portfolio and Stock Shopping Cart button.
 *
 * @package Astrodj
 */

?>
<div id="single_shop_form" class="astrodj-shop-modal modal-shop" style="display: none;">
  <div class="modal-wrapper">
    <div class="modal-header">
      <p class="title">Эту фотографию можно купить</p>
      <p class="small">Размер фотографии: 10 × 15 см<br>
        Оформление: белое паспарту и рамка А5
      </p>
      <p class="small">
        Напечатана на оригинальной фотобумаге Canon высокого качества.
        <br>
        <a href="<?php echo home_url( 'by-landscape/' ); ?>" target="_blank">Смотреть примеры</a>
      </p>
      <p class="summary">
        <span class="small">Цена: <?php echo get_option( 'astrodj_price' ); ?> &#8381;</span>
      </p>
      <p class="summary"><span class="small">После отправки заявки я пришлю ссылку на безопасное оформление заказа через Авито Доставку.</span></p>
      <p class="summary"><span class="small">Заполните форму ниже, и я свяжусь с вами по электронной почте.</span></p>
    </div>
    <div class="modal-content">
      <form class="form singleShopForm" method="post">
        <div class="form-group">
          <input type="email" name="email" value="" class="form-control required" id="email" placeholder="Ваш Email">
        </div>
        <button type="submit">
          <svg class="icon icon-loader-ring loader" viewBox="0 0 32 32">
            <path d="M16,5.2c5.9,0,10.8,4.8,10.8,10.8S21.9,26.8,16,26.8S5.2,21.9,5.2,16H0c0,8.8,7.2,16,16,16s16-7.2,16-16S24.8,0,16,0V5.2z"/>
          </svg>
          <span class="text">Заказать</span>
        </button>
        <input type="hidden" name="id" value="<?php echo get_the_ID(); ?>">
      </form>
    </div>
  </div>
</div>
<div id="success_modal" class="modal-shop modal-success" style="display: none;">
  <div class="modal-wrapper">
    <div class="modal-content">
      <h5 class="title">Ваш заказ принят,</h5>
      <p>в течение 24 часов я пришлю вам на почту ссылку для заказа на Авито.</p>
      <h6 class="thanks">Спасибо за понимание</h6>
    </div>
  </div>
</div>
