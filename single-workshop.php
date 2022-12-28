<?php get_header(); ?>
    <?php $author_name = get_the_author_meta('display_name'); ?>
    <div class="container">
    <?php while(have_posts()): the_post(); ?>
        <?php the_tags('<div id="post-tags" class="post-tags">', "", "</div>"); ?>
        <div id="post-meta" class="post-meta">
            <div class="post-meta__icon">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="38" height="38" viewBox="0 0 38 38">
                    <defs>
                        <linearGradient id="linear-gradient" x1="1" y1="0.5" x2="0" y2="0.5" gradientUnits="objectBoundingBox">
                        <stop offset="0" stop-color="#bf1d1d"/>
                        <stop offset="1" stop-color="#f0bfbf"/>
                        </linearGradient>
                    </defs>
                    <g id="Ellipse_8" data-name="Ellipse 8" fill="#111" stroke="#575757" stroke-width="1">
                        <circle cx="19" cy="19" r="19" stroke="none"/>
                        <circle cx="19" cy="19" r="18.5" fill="none"/>
                    </g>
                    <g id="pencil" transform="translate(7.998 8.031)">
                        <path id="Path_1706" data-name="Path 1706" d="M19.36,14.753H16.672L16,17.441l3.36,3.36,2.688-.672V17.441H19.36ZM36.242,3.089l0,0L33.712.559a2.02,2.02,0,0,0-2.851,0L28.925,2.495a.5.5,0,0,0,0,.714L33.586,7.87a.5.5,0,0,0,.714,0l1.936-1.936A2.013,2.013,0,0,0,36.242,3.089Z" transform="translate(-15.326)" fill="#c12424" opacity="0.491"/>
                        <path id="Path_1707" data-name="Path 1707" d="M.017,112.531A1.008,1.008,0,0,0,.835,113.7a1.019,1.019,0,0,0,.35,0l2.827-.679L.694,109.7Zm17.3-11.468L12.655,96.4a.509.509,0,0,0-.718,0L1.346,106.994H4.033v2.688H6.721v2.688l10.594-10.593a.5.5,0,0,0,0-.713Z" transform="translate(0 -92.241)" fill="url(#linear-gradient)"/>
                    </g>
                </svg>
            </div>
            <div class="post-meta__data">
                <?php $display_name = get_the_author_meta('display_name'); ?>
                <h5><?= $display_name; ?></h5>
                <date><?= get_the_date("d.m.Y"); ?></date>
            </div>
        </div>
        <?php the_content(); ?>
    <?php endwhile; ?>
    </div>
<?php get_footer(); ?>