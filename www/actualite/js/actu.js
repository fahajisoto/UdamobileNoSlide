function recuperation(){   
    $('#divRss').FeedEk({
    FeedUrl : 'http://udamobile.u-clermont1.fr/v2/restaurant/',
    MaxCount : 5,
    ShowDesc : true,
    ShowPubDate:true,
    DescCharacterLimit:100,
    TitleLinkTarget:'_blank'
    });
}