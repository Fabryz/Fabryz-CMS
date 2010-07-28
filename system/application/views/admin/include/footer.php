
        <div id="admin-footer">
            <p id="admin-copyright">Copyright &copy;2010 <strong>Fabrizio Codello</strong> All rights reserved</p>
            <p id="admin-credits"><?php echo anchor( "#", CMS_TITLE." - ".CMS_VERSION, 'title="Check out the official '.CMS_TITLE.' site"');  ?></p>
            <div class="clearer"></div>
        </div> <!-- /admin-footer  -->
        <div id="admin-stats">
            <p><?php echo "It needed ".$this->benchmark->elapsed_time()." seconds and ".$this->benchmark->memory_usage()." to load this stuff"; ?></p>
        </div> <!-- /admin-stats  -->
    </div> <!-- /admin-wrapper  -->
</body>
</html>