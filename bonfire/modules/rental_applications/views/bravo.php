<section id="rental_application">
<div class="row-fluid">

    <!--crumbtrail-->

    <div class="span8">
        <div class="message"><!--display a dynamic message to user with JS--></div>
    </div>
    <div class="span2 offset1">
        <!--controls-->
        <?php if ($page >=4 && $page < 10): ?>
        <div class="pull-right"><button class="btn btn-prev">Prev</button><button class="btn btn-success btn-next">Next</button></div>
        <?php endif; ?>
    </div>
</div>
<div class="row-fluid well-large">


<?php if ($page==1):?>
<h3>Choose A Category</h3>
    <?php //var_dump($instruments); ?>
<div class="span11 offset1">
    <?php foreach($groups as $group):
            foreach($group->children as $cat):
    ?>

    <div class="pull-left well-small" >
        <a href="<?php echo $group_url. 'group=' .$cat->category_id;?>" class="instrument_link">
            <!---description--->
            <h4 style="width:150px; text-align: center; height: 3em;"><?php echo $cat->category_name;?></h4>
            <!---img-->

        </a>
    </div>
    <?php endforeach; endforeach;?>
    <div class="clearfix"></div>
</div>
<?php endif; ?>


<?php if ($page==2):?>
    <h3>Select An Instrument </h3>
    <?php //var_dump($instruments); ?>
    <div class="span11 offset1">
        <?php foreach($instruments as $instrument):?>

            <div class="pull-left well-small" >
                <a href="<?php echo $instrument_url. 'instrument=' .$instrument->product_id;?>" class="instrument_link">
                    <!---description--->
                    <h4 style="width:150px; text-align: center; height: 3em;"><?php echo $instrument->product_name;?></h4>
                    <!---img-->

                </a>
            </div>
            <?php endforeach;?>
        <div class="clearfix"></div>
    </div>
<?php endif; ?>


<?php if ($page==3):?>
    <h3>Instrument: <?php echo $selected_instrument->product_name; ?> </h3>
    <?php //var_dump($instruments); ?>
    <div class="span11 offset1">
        <?php echo $selected_instrument->product_description; ?>
        <div class="clearfix"></div>
    </div>
<?php endif; ?>


<?php if ($page==4):?>
    <h3>Maintenance and Replacement</h3>

    <p>MAINTENANCE & REPLACEMENT OPTION (M&R) - Highly recommended by band directors!</p>


    <p>The monthly M&R option protects your instrument in the event of damage or on-going maintenance issues.
        Coverage also includes a complete inspection, cleaning of brass and complete regulation of woodwinds as needed.
        We recommend this once a year. If the instrument you are renting needs repair or maintenance, a loaner will be provided.</p>

    <p> M&R also replaces the instrument in case of fire or theft, not for negligence or abuse.
        To make a claim for replacement due to fire or theft, you must file an official report with the local police
        department and provide a copy of the report to The Music Shoppe, Inc. </p>

    <p> M&R will be included with the rental of any string instrument.</p>

    <strong>
        <?php if (! empty($m_r_information) ): ?>
        <input type="checkbox" name="<?php echo $m_r_information[1][0]->formfield_label; ?>" id="optional_m_r" checked="checked"/> Include this option in my rental.
        <?php endif; ?>
        Price: $<?php echo $m_r_price;?></strong>
<?php endif; ?>


<?php if ($page==5):?>
<h3>Rental Invoice</h3>

<p>Please review the following pricing and provide us with the requested information to complete your online instrument rental.
    Your application will then be submitted. Once reviewed, you will receive an e-mail receipt containing a copy of your rental
    contract or purchase agreement.</p>

<div class="totalbox">
    <h4>Summary</h4><p></p>
    <div class="row-fluid">
        <div class="span2">Total Due Now: </div>
        <div class="span2"><div class="pull-right">$<?php echo number_format($monthly_rental + $m_r_price,2);?></div></div>
    </div>
    <div class="row-fluid">
        <div class="span4"><hr></div>
    </div>
    <div class="row-fluid">
        <div class="span2">Monthly Total: </div>
        <div class="span2"><div class="pull-right">$<?php echo number_format($monthly_rental + $m_r_price,2);?></div></div>
    </div>
    <div class="row-fluid">
    <div class="span4"><hr></div>
    </div>
    <div class="row-fluid">
        <div class="span2">First Installment Due: </div>
        <div class="span2"><div class="pull-right"><?php echo $due_date;?></div></div>
    </div>
</div>
<p></p>

<div class="pricebox">
    <h4>Detailed Rental Fees</h4><p></p>
    <div class="subbox">
        <div class="row-fluid">
            <div class="span2">Instrument Price: </div>
            <div class="span2"><div class="pull-right">$<?php echo $price_instrument;?></div></div>
        </div>
        <div class="row-fluid">
            <div class="span2">Instrument Tax: </div>
            <div class="span2"><div class="pull-right">$<?php echo $tax_instrument;?></div></div>
        </div>
        <div class="row-fluid">
            <div class="span4"><hr></div>
        </div>
        <div class="row-fluid">
            <div class="span2">Instrument Total Cost: </div>
            <div class="span2"><div class="pull-right">$<?php echo $cost_instrument;?></div></div>
        </div>
        <div class="row-fluid">
            <div class="span2">Instrument Service Charge: </div>
            <div class="span2"><div class="pull-right">$<?php echo $service_charge;?></div></div>
        </div>
        <div class="row-fluid">
            <div class="span4"><hr></div>
        </div>
        <div class="row-fluid">
            <div class="span2">Total of Payments: </div>
            <div class="span2"><div class="pull-right">$<?php echo $total_payment?></div></div>
        </div>

        <div class="row-fluid">
            <div class="span4"><hr></div>
        </div>

    </div>

    <br/><br/><br/>
    <div class="subbox">
        <div class="row-fluid">
            <div class="span2">Monthly Rental Fee:</div>
            <div class="span2"><div class="pull-right"> $<?php echo $monthly_rental;?></div></div>
        </div>
        <div class="row-fluid">
            <div class="span2">Monthly Maintenance and Replacement:  </div>
            <div class="span2"><div class="pull-right">$<?php echo $m_r_price;?></div></div>
        </div>
        <div class="row-fluid">
            <div class="span4"><hr></div>
        </div>
        <div class="row-fluid">
            <div class="span2">Total Monthly Fee:</div>
            <div class="span2"><div class="pull-right"> $<?php echo number_format($monthly_rental + $m_r_price,2);?></div></div>
        </div>
        <div class="row-fluid">
            <div class="span4"><hr></div>
        </div>
        <div class="row-fluid">
            <div class="span2">Number of Monthly Payments: </div>
            <div class="span2"><div class="pull-right"><?php echo $installments;?></div></div>
        </div>
        <div class="row-fluid">
            <div class="span2">Final Payment: </div>
            <div class="span2"><div class="pull-right">$<?php echo $final_payment;?></div></div>
        </div>
        <div class="row-fluid">
            <div class="span4"><hr></div>
        </div>
        <div class="row-fluid">
            <div class="span2">First Installment Due: </div>
            <div class="span2"><div class="pull-right"><?php echo $due_date;?></div></div>
        </div>

    </div>

</div>

    <?php endif; ?>

<!---contract form-->

<?php if ($page==6):?>
<h3><?php echo $general_information[0]->formsection_name;?></h3>
<form method="POST" action="#" class="pageData">
    <input type="hidden" name="formSection" value="<?php echo $general_information[0]->formsection_name;?>"/>
    <?php $count = 0; ?>
<div class="span3">
    <?php foreach($general_information[1] as $item ):?>

    <?php if ($count == 4 || $count == 9): //make a new column?>
                 </div><div class="span3">
            <?php endif;?>

    <div id="<?php echo $item->formfield_name;?>Group">
        <?php echo form_input($item->formfield_name,$this->session->userdata('field_'.$item->formfield_name), $item->formfield_label, '','<p><span class="help-inline"></span></p>');?>
    </div>
    <?php $count++;?>
    <?php endforeach;?>
</div>
</form>
    <?php endif;?>

<?php if ($page==7):
    $references = "{$employer_information[0]->formsection_name},{$spouse_information[0]->formsection_name},{$reference_information[0]->formsection_name}";?>
    </div><div class="row-fluid"><!--start a new row-->
    <form method="POST" action="#" class="pageData">
        <input type="hidden" name="formSection" value="<?php echo $references;?>"/>
        <h3><?php echo $employer_information[0]->formsection_name;?></h3>

        <?php $count = 0; ?>
    <div class="span3">
        <?php foreach($employer_information[1] as $item ):?>

        <?php if ($count == 4 || $count == 9): //make a new column?>
                 </div><div class="span3">
            <?php endif;?>

        <div id="<?php echo $item->formfield_name;?>Group">
            <?php echo form_input($item->formfield_name,$this->session->userdata('field_'.$item->formfield_name), $item->formfield_label, '','<p><span class="help-inline"></span></p>');?>
        </div>

        <?php $count++;?>
        <?php endforeach;?>
    </div>


</div><div class="row-fluid"><!--start a new row-->
    <h3><?php echo $spouse_information[0]->formsection_name;?></h3>
    <?php $count = 0; ?>
<div class="span3">
    <?php foreach($spouse_information[1] as $item ):?>

        <?php if ($count == 5 ): //make a new column?>
            </div><div class="span3">
            <?php endif;?>

        <div id="<?php echo $item->formfield_name;?>Group">
            <?php echo form_input($item->formfield_name,$this->session->userdata('field_'.$item->formfield_name), $item->formfield_label, '','<p><span class="help-inline"></span></p>');?>
        </div>

        <?php $count++;?>
        <?php endforeach;?>
</div>

</div><div class="row-fluid"><!--start a new row-->
<h3><?php echo $reference_information[0]->formsection_name;?></h3>
    <?php $count = 0; ?>
        <div class="span3">
        <?php foreach($reference_information[1] as $item ):?>

    <?php if ($count == 3 ): //make a new column?>
            </div><div class="span3">
            <?php endif;?>

    <div id="<?php echo $item->formfield_name;?>Group">
        <?php echo form_input($item->formfield_name,$this->session->userdata('field_'.$item->formfield_name), $item->formfield_label, '','<p><span class="help-inline"></span></p>');?>
    </div>

    <?php $count++;?>
    <?php endforeach;?>
        </div>


    </form>
    <?php endif;//end of page 8?>


<?php if ($page==8):?>
        <form method="POST" action="#" class="pageData">
        <input type="hidden" name="formSection" value="<?php echo $payment_information[0]->formsection_name;?>"/>
<h3><?php echo $payment_information[0]->formsection_name;?></h3>

    <?php $count = 0; ?>
<div class="span3">
    <?php foreach($payment_information[1] as $item ):?>

    <?php if ($count == 4 || $count == 9): //make a new column?>
        <!--/div><div class="span3"-->
        <?php endif;?>

    <div id="<?php echo $item->formfield_name;?>Group">
        <?php echo form_input($item->formfield_name,$this->session->userdata('field_'.$item->formfield_name), $item->formfield_label, '','<p><span class="help-inline"></span></p>');?>
    </div>

    <?php $count++;?>
    <?php endforeach;?>
</div>
        <form>
    <?php endif;?>

<?php if ($page==9):?>
<h3>Terms of Service</h3>

<div class="span8 offset2">
    <p>Please read our terms and conditions. After reading, initializing at the end confirms your in agreement.</p>

    <h5>TERMS AND CONDITIONS</h5>

    <p>This contract, effective as of the date stated, is between The Music Shoppe, Inc. ("Dealer"), and the person
        listed on the reverse side, ("Renter"). The Music Shoppe, Inc. ("Dealer"), will be responsible for
        delivering instrument, and customer service needs as it pertains to this contract. Subject to the terms and
        conditions stated below, Dealer agrees to rent to Renter, the instrument identified on the reverse side,
        (along with any improvements, alterations or substitutions shall be referred to as "the Property").
    </p>

    <p>Term of Rental. The term of the contract shall be for a minimum of two months, beginning as of the stated
        date and ending on the date the Property is either returned voluntarily or involuntarily to Dealer or
        purchased from Dealer as provided for herein.
    </p>

    <p>Rent. Renter agrees to pay to Dealer as rent for the Property the monthly rental fee set forth on the reverse
        side of this page. Renter remains obligated to pay the monthly rental fee for every month or fraction of a
        month that renter continues to retain possession of the Property and has not returned the Property to
        Dealer. Renter agrees to bring the account current upon the return of the Property to Dealer and
        acknowledges that Dealer is entitled to retain all payment previously made under this contract.
    </p>

    <p>Service Charges. If any monthly rental fee is not paid within ten days after its due date, a $5.00 late fee
        will be assessed against renter. In addition, there will be a $25 fee for any NSF checks, ACH transactions
        or denied credit card debits used as payment for this rental.
    </p>
    <p> Location of Property. Renter shall not permit the Property to be removed from his or her possession during
        the term of this contract, without the prior written consent of Dealer. Renter agrees to notify Dealer in
        writing within 10 days as to any change in the location of Renter and/or the location of the Property.
    </p>

    <p>
        Inspection and Care of Property. Renter has inspected the Property prior to delivery and acknowledges that the
        Property is in good condition and repair and accepts the Property in such good condition and repair. Renter
        agrees to use the Property in a careful and proper manner and to comply with Dealer's or the manufacturer's
        instructions relating to the posses­sion, use, maintenance, repair, and operation of the Property. The Property
        shall be used only as a musical instrument and for no other purpose.
    </p>

    <p>
        Identification of Property. Renter agrees that it will make no alterations to or in the Property without
        obtaining prior written permission from Dealer. Any additions to and improvements to the Property of any kind
        shall immediately become Dealer's property and subject to the terms of this contract.
    </p>

    <p>
        Maintenance and Repair. All repairs shall be performed by the Dealer. Renter shall at all times during the term
        of this contract keep the Property in good repair, condition and working order. The cost of maintenance and
        repair will be borne solely by the Renter unless Renter pays a monthly maintenance fee. Maintenance fee
        covers any repair or replacement of any part to put instrument in playing condition. The maintenance fee
        does not include mouthpieces, mouthpiece caps, ligatures, reeds, swabs or any accessories. The maintenance
        fee does not cover loss or damage from ill usage, abuse, neglect, fire, theft or repair performed by another
        dealer.
    </p>

    <p>
        Replacement. Renter will have the option of replacement coverage for the following; burglary or theft, fire,
        lightning, flood and water, destruction, vandalism, smoke, wind and hurricane. In the event that this rental
        account becomes past due the replacement coverage will be void. In such a case, if the instrument is lost,
        stolen or damaged, beyond repair the Renter will be liable for the full amount of any damage incurred or in
        the event of a total loss, 100% of the full replacement value.
    </p>

    <p>
        Dealer's Right of Inspection. Dealer shall at all reasonable times and places have the right to inspect the
        Property and observe its use. Renter shall immediately notify Dealer of any attachment or other judicial
        process affecting the Property.
    </p>

    <p>
        Return of Property. On the expiration or early termination of this contract, Renter shall return the Property to
        Dealer in good repair, ordinary wear and tear resulting from proper use alone excepted, to Dealer's place of
        business. Such conditions for the return of the Property are subject to Renter's option to purchase the
        Property as set forth below.
    </p>

    <p>
        Renter's Option to Purchase Property. Dealer grants to Renter the option to purchase the Property, provided
        that Renter notifies Dealer of his or her intention to purchase and provided further that Renter completely
        performs all of the terms and conditions of this contract on Renter's part to be performed, including full
        payment of the rental payments. Should Renter exercise the option to purchase, all of the rental payments
        paid shall be applied to the purchase price of the Property. On receipt of the balance of the purchase price
        by Dealer, together with applicable unpaid taxes, Dealer will transfer title to the Property to Renter.
        The instrument will remain the property of Dealer and will not become the property of Renter until the full
        purchase price (including the total cash price, plus any service charges and late fees) has been paid. This
        option is available only if Renter selected the Rent-to-Own contract terms.
    </p>

    <p>
        Renter's Option to Rent Alternate Property. Renter shall have the right to rent an instrument other than the
        instrument listed on the reverse page;, but if and only if the following condi­tions are met;<br />
        a) Renter returns the instrument listed on the reverse page to Dealer in good condition;<br />
        b) Renter's account with Dealer is current; and <br />
        c) Renter completes and executes a new instrument rental contract for the newly rented instrument.<br />
    </p>

    <p>
        Events Constituting Default. The following events shall constitute default under this agreement. <br />
        (a) The nonpayment by Renter for a period of ten days of any sum required to be paid by Renter that is not
        cured within 15 days of the payment due date; <br />
        (b) The nonperformance by Renter of any other term, covenant, or condition of this lease that is not cured
        within ten days after notice of nonperformance from Dealer; <br />
        (c) Any affirmative act of insolvency by Renter, or the filing by Renter of any petition under any bankruptcy,
        reorganization, insolvency, or moratorium law, or any law for the relief of, or relating to, debtors;
        or<br />
        (d) The subjection of any Renter's property to any levy seizure, assignment, application, or sale for or by any
        creditor or governmental agency.<br />
    </p>

    <p>
        Dealer's Rights on Default. On the occurrence of any of the events of default described above, Dealer, without
        notice to or demand on Renter, may retake possession of the Property and rent the Property for such period
        and for such amount and to such persons as Dealer shall elect, and shall apply the proceeds of any such
        renting, after deducting all costs and expenses, including collection costs, legal expenses, and attorneys'
        fees, incurred in connection with the recovery, repair, storage, and renting of the Property in payment
        of the rental payments and other obligations due from Renter to Dealer, Renter remaining responsible for
        any deficiency. Dealer is entitled to pursue all legal remedies available to it to enforce the terms of
        this contract, including the pursuit of a collection action to collect any amount due under this contract.
        Renter will be responsible for all costs incurred by Dealer, including collection costs, legal expenses,
        and attorneys' fees, to enforce the terms of this contract against Renter. Any amounts turned over for
        formal collection will incur interest at the rate of 18% per annum from the date of default.
    </p>

    <p>
        Rights of Reinstatement. If Renter fails to make a monthly rental fee when due, Renter may reinstate
        this contract without losing any rights or options previously acquired if he or she brings his or
        her account current within 15 days of the payment due date. If Renter voluntarily returns the Property
        to Dealer during the applicable 15-day reinstatement period, the right to reinstate this contract shall
        be extended for a period of 30 days following the date of return of the property. Upon reinstatement,
        Dealer shall provide Renter with the same instrument or a substitute instrument of comparable quality and
        condition. If monthly rental payments are over 10 days late for three consecutive months or run over
        30 days late at any time, Renter must return the property to Dealer, and all rental fees will be forfeited
        unless the balance of payments and any late fees are paid in full.
    </p>

    <p>
        Ownership of Property. The Property is the sole property of Dealer, and Renter shall have no right, title or
        interest in the Property except as expressly set forth in this contract.
    </p>

    <p>
        Assignment. Without the prior written consent of Dealer, Renter shall not;<br />
        (a) Assign, transfer, or pledge this rental/purchase contract or any interest in the Property; <br/>
        (b) Sublet or lend the Property; <br/>
        (c) Permit the Property to be used by anyone other than Renter; or <br/>
        (d) Resell the Property before any option to purchase has been completed.<br />
        Dealer may assign or transfer this contract, the instrument or its rights to receive payments under this
        contract at any time without notice to or approval from Renter.
    </p>

    <p>
        Notices. Any communications between Dealer and Renter, payments and notices provided in this agreement to be
        given or made shall be given or made by mailing them to Dealer at its principle place of business and to
        Renter at the address printed on the reverse side or to such other addressers as either party may indicate
        in writing.
    </p>

    <p>
        Limitation of Warranties. Renter acknowledges that the Property is of a size, design, capacity and manufacture
        selected by Renter. Dealer is not a manufacturer of the Property and has not made and does not make any
        representation, warranty, or covenant, express or implied. With respect to the condition, quality,
        durability, suitability, or merchantability of the Property. Dealer will take any reasonable steps to
        make available to Renter any manufacturer's or similar warranty applicable to the Property. Dealer shall
        not be liable to Renter for any liability, loss or damage caused or alleged to be caused directly or
        indirectly by the Property, by any inadequacy of, or defect in, the Property or by any incident in
        connection with the Property.
    </p>

    <p>
        General Representations. Renter warrants and represents that he or she is over 18 years of age and has the
        capacity to enter into this contract and that the information stated on the reverse page is true and
        correct. Renter further represents that he or she has read and agrees to the conditions stated in this
        contract.
    </p>

    <hr />
    <form method="POST" action="#" class="pageData">
        <input type="hidden" name="formSection" value="<?php echo $terms_information[0]->formsection_name;?>"/>
        <?php foreach($terms_information[1] as $item ):?>
        <div id="<?php echo $item->formfield_name;?>Group">
            <?php echo form_input($item->formfield_name,$this->session->userdata('field_'.$item->formfield_name), $item->formfield_label, '','<p><span class="help-inline"></span></p>');?>
        </div>
        <?php endforeach;?>
    </form>
</div>

    <?php endif;?>

<?php if ($page==10):?>
<div class="row">
    <div class="span5">
        <h3>Thank You!</h3>
        <p>Your application has been submitted. You may now return to our home page. You will receive a copy of your Music Shoppe application via e-mail once it has been reviewed.</p>

        <p></p>

        <p><a href="http://www.themusicshoppe.com">Return Home</a></p>
    </div>
    <div class="span6 offset1">
        <?php

        $image_properties = array(
            'src' => 'assets/images/musicshoppe.jpg',
            'alt' => 'Rental App confirmation image'
        );

        echo img($image_properties); ?>
    </div>
</div>
<script>
    sendReceipts = 'yes';
</script>
<?php endif;?>

</div><!--end of page row-->

<div class="row-fluid">
    <!--controls-->
    <!--pagenation-->
    <?php echo $pagination;?>
</div>

<!--js config variables-->
<script>
    page = <?php echo $page;?>;
    resource = '<?php echo $resource;?>';
</script>