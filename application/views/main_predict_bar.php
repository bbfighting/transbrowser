                        <form role="form" method="post" action="<?php echo WEBSITE_PATH;?>predict" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="comment">Paste or type your sequence in FASTA format here :</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(EX : <button type="button" class="btn btn-default btn-xs" id="example1">#1</button>)
                                <textarea class="form-control" rows="5" id="comment" name="txt_input" placeholder="type FASTA format"></textarea>
                            </div>
                            <div class="form-group">                    
                                <label for="comment">Or upload a file in FASTA format :</label>
                                <input type="file" name="FILE"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(FASTA format EX : <button type="button" class="btn btn-default btn-xs" id="example2">#1</button>)
                            </div>
                            <div class="form-inline">
                                <button type="submit" class="btn-primary">Submit</button>
                                <button type="reset" class="btn-primary">Reset</button>                
                            </div>
                        </form>