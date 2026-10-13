<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <?php foreach ($inventories as $inventory) { ?>
    <url>
        <loc><?php echo $inventory['url']; ?></loc>
        <lastmod><?php echo $inventory['updated_at']; ?></lastmod>
        <priority>0.7</priority>
        <changefreq>Daily</changefreq>
    </url>
    <?php } ?>
</urlset>