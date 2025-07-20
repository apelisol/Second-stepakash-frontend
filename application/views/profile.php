<div class="col-11 col-sm-11 col-md-6 col-lg-5 col-xl-3 mx-auto align-self-center py-4">        
                <h3 class="mb-4"><span class="text-secondary fw-light">Update</span><br/>Your Phone</h3>            
                   
                   <form action="<?php echo base_url() ?>Main/updatephone" method="POST">
                    <div class="form-floating is-valid mb-3">
                        <input type="hidden" value="<?php echo  $this->session->userdata('wallet_id'); ?>" class="form-control"
                            placeholder="wallet_id" name="wallet_id" id="wallet_id">
                            
                        <input type="tel" value="<?php echo  $this->session->userdata('phone'); ?>" class="form-control"
                            placeholder="Phone" name="phone" id="emailphone">
                        <label for="emailphone">Phone</label>
                    </div>
                 
              
                            
                    <div class="row ">
                    <div class="col-12 d-grid">
                    <button type="submit" class="btn btn-default btn-lg shadow-sm">Update Phone</button>
                    </div>
                    </div>
                    </form>
            </div>