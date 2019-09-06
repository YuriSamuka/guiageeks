/*Este js é responsavel por carregar as informações da pagina home. Ex: poster, nome do conteudo, quantidade de views e etc*/

$(document).ready(function(){
    $.get("index.php?acao=carregar_front", function(data, status){
        var dados = JSON.parse(data);
        carregaUltimosAdicionandos(dados.ultimos_adicionandos.anime, 'anime');
        carregaUltimosAdicionandos(dados.ultimos_adicionandos.manga, 'manga');
        carregaUltimosAdicionandos(dados.ultimos_adicionandos.hq_volume, 'hq_volume');
        carregaRanking(dados.ranking.anime, 'anime');
    });
});

function carregaUltimosAdicionandos(conteudos, tipoConteudo) {
    /*Carrega os "destaques da semana"*/
    /*Passa por cada card carregando o link de direncionamento e a imagen poster do card*/
    $(".container-ultimos-" + tipoConteudo + "s-adicionandos .link-poster-conteudo-card").each(function (i) {
        $(this).attr("href", window.location.href + tipoConteudo + "/" + conteudos[i].id_conteudo);
        $(this).children().attr("src", conteudos[i].poster);
    });

    /*Passa por cada card carregando o link de direncionamento do titulo e o seu texto*/
    $(".container-ultimos-" + tipoConteudo + "s-adicionandos .link-titulo-conteudo-card").each(function (i) {
        $(this).attr("href", window.location.href + tipoConteudo + "/" + conteudos[i].id_conteudo);
        $(this).html(conteudos[i].titulo);
    });

    /*Passa por cada card adicionando a informação "adicionando á x tempo"*/
    $(".container-ultimos-" + tipoConteudo + "s-adicionandos .icon-clock").each(function (i) {
        $(this).append(conteudos[i].adicionado);
    });

    /*Passa por cada card adicionando a informação "quantidade de views"*/
    $(".container-ultimos-" + tipoConteudo + "s-adicionandos .icon-views").each(function (i) {
        $(this).append(conteudos[i].views);
    });

    /*Passa por cada card adicionando a informação "quantidade de assinantes"*/
    $(".container-ultimos-" + tipoConteudo + "s-adicionandos .icon-qtd-assinantes").each(function (i) {
        $(this).append(conteudos[i].assinantes);
    });
}

function carregaRanking(conteudo, tipoConteudo) {
    /*Carrega o ranking "mais vistos"*/
    /*Passa por cada do item do ranking carregando as informações: link de direncionamento, nome do conteudo, sobre o conteudo*/
    console.log(conteudo);
    $(".infos-ranking-anime").each(function (i) {
        $(this).attr("href", conteudo[i].poster);
        $(this).find("a").html(conteudo[i].titulo);
        $(this).find("p").html(conteudo[i].sobre);
    });
    $(".link-imagen-poster-ranking").each(function (i) {
        /*Passa por cada do item do ranking carregando as informações: link de direncionamento, nome do conteudo, sobre o conteudo*/
        console.log(conteudo[i].poster);
        $(this).attr("href", conteudo[i].poster);
        $(this).children().attr("src", conteudo[i].poster);
    });
}