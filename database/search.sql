SELECT distinct(d.url) as url ,
    d.content_type as content_type,
    t.content as title,
    c.content as content,
    MATCH(c.content) AGAINST(?) as score
    from document d,field t, field c where
        d.collection_id = '?'
        and d.id=t.document_id and t.name='title'
        and  d.id=c.document_id
        and c.name='content' order by score desc;
