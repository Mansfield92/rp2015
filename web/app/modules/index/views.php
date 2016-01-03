<?php
    $role = intval($_SESSION['login_role']);
?>
<section class="index">
    <div class="container" id="grid">
        <?php if($role >= 4){?>
        <div class="grid-item icons icon-depot" data-action="depo-list">
            <img src="icons/depo_small.svg" />
            <div class="grid-item_text">
                Depa
            </div>
        </div><div class="grid-item icons icon-train" data-action="trains-list">
            <img src="icons/train_small.svg" />
            <div class="grid-item_text">
                Lokomotivy
            </div>
        </div><?php }?><?php if($role == 2 || $role > 4){?><div class="grid-item " data-action="users-list">
            <img src="icons/team.svg" />
            <div class="grid-item_text">
                Zaměstnanci
            </div>
        </div><?php }?><?php if($role >= 4){?><div class="grid-item " data-action="servis-list">
            <img src="icons/maitanance_small.svg" />
            <div class="grid-item_text">
                Servis
            </div>
        </div><?php }?><?php if($role == 1 || $role > 4){?><div class="grid-item " data-action="route-list">
            <img src="icons/route.svg" />
            <div class="grid-item_text">
                Trasy
            </div>
        </div><?php }?><?php if($role == 1 || $role == 3 || $role > 4){?><div class="grid-item " data-action="plans-list">
            <img src="icons/plan.svg" />
            <div class="grid-item_text">
                Plánování
            </div>
        </div><?php }?><?php if($role > 4){?><div class="grid-item grid-item-big" data-action="reports-list">
            <img src="icons/graph_small.svg"/>
            <div class="grid-item_text">
                Reporty
            </div>
        </div><?php }?>
    </div>
</section>