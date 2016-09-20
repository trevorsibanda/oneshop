    <section class="blog text-center" style="margin-top: 50px;" id="blog">
        <div class="container-fluid">
            <div class="row">
                <h4>From our blog</h4>
            <?php foreach( $blog_feed as $feed ): ?>    
                <div class="col-md-4">
                    <article class="blog-post">
                        <figure>
                            <a href="<?=  $feed['img']  ?>" class="single_image">
                                <div class="blog-img-wrap">
                                    <div class="overlay">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <img src="<?=  $feed['img']  ?>" class="img-responsive"  style="max-height: 225px;" alt="FRom the 263Shop blog"/>
                                </div>
                            </a>
                            <figcaption>
                            <h2><a href="#" class="blog-category" data-toggle="tooltip" data-placement="top" data-original-title="by <?= $feed['author'] ?>"><?= date('Y-m-d' , strtotime($feed['pubDate']) ) ?></a></h2>
                            <p><a href="<?= $feed['link'] ?>" target="_blank" ><?=  $feed['title'] ?></a></p>
                            </figcaption>
                        </figure>
                    </article>
                </div>
            <?php endforeach; ?>
                
				
                <a href="/home/blog" class="btn btn-ghost btn-accent btn-small">More news</a>
            </div>
        </div>
    </section>
