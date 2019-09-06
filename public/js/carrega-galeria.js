/*Este js é responsavel por carregar as informações da pagina galeria. Ex: poster, nome do conteudo e etc*/

$(document).ready(function(){
    var params = window.location.pathname.split('/');
    params.splice(params.indexOf(""), 1);
    if (params.indexOf("guiageeks") !== -1){
        params.splice(params.indexOf("guiageeks"), 1);
    }
    params.splice(params.indexOf("galeria"), 1);
    var tipoConteudo = params[0];
    var tipoFiltro = params[1];
    var letra = params[2];
    var pagina = params[3];
    carregaLinksPaginacao(tipoConteudo, tipoFiltro, letra);
    $.get("galeria?tipo_conteudo=" + tipoConteudo + "&tipo_filtro=" + tipoFiltro + "&letra=" + letra + "&pagina=" + pagina + "&acao=carregar_front", function(data, status){
        console.log("galeria?tipo_conteudo=" + tipoConteudo + "&tipo_filtro=" + tipoFiltro + "&letra=" + letra + "&pagina=" + pagina + "&acao=carregar_front");
        var dados = JSON.parse(data);
        carregaCards(dados, 'anime');
    });
});

function carregaCards(conteudos, tipoConteudo) {
    /*Passa por cada card carregando o link de direncionamento e a imagen poster do card*/
    $(".link-poster-conteudo-card").each(function (i) {
        $(this).attr("href", $(this).attr("href") + "/" + tipoConteudo + "/" + conteudos[i].id_conteudo);
        $(this).children().attr("src", conteudos[i].poster);
    });

    /*Passa por cada card carregando o link de direncionamento do titulo e o seu texto*/
    $(".link-titulo-conteudo-card").each(function (i) {
        $(this).attr("href", $(this).attr("href") + "/" + tipoConteudo + "/" + conteudos[i].id_conteudo);
        $(this).html(conteudos[i].titulo_ingles);
    });
}

function carregaLinksPaginacao(tipoConteudo, tipoFiltro, letra) {
    $(".page-link").each(function (i) {
        $(this).attr("href", $(this).attr("href") + "/galeria/" + tipoConteudo + "/" + tipoFiltro + "/" + letra + "/" + $(this).text());
    });
}