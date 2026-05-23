<div class="row account-header">
    <div class="col-md-12 fixed-menu-button"  style="">
        <div style="margin-left: 5px;"><a href="/account/" class="btn flat-btn <?=session()->get('account_tab')
          =='account'?'active_menu':'';?>">Parent(<b>ID <?= session()->get('userresult')[0]['parent_id'] ?></b>)</a></div>
        <div><a href="/account/students" class="btn flat-btn <?=session()->get('account_tab')=='students'?'active_menu':'';?>">Students|Register</a></div>
        <div><a href="/account/invoice" class="btn flat-btn" target="_blank">Invoice</a></div>
        <div><a href="/pod/index" class="btn flat-btn <?=session()->get('account_tab')
          =='pod'?'active_menu':'';?>">POD Sign-up</a></div>
    </div>
</div>