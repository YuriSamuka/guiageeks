root = "";
$(document).ready(function(){

    /*EVENTOS DAS OPÇOES DO MENU DO TOP*/
    /*Mostra o menu do usuario e oculta outras menus que estejam abertos*/
    $(".thumbnail-usuario-topo").click(function () {
        var e=window.event||e;
        $(".dropdown-usuario").addClass("dropdown-fadein");
        $('.dropdown-notificacao').removeClass("dropdown-fadein");
        $(".lista-notificacoes").fadeOut();
        $(".dropdown-menu").fadeOut();
        $(".lookup-busca").fadeOut();
        e.stopPropagation();
    });
    /*Mostra o notificações e oculta outras menus que estejam abertos*/
    $(".icon-notificacao").click(function() {
        var e=window.event||e;
        $(".lista-notificacoes").fadeIn();
        $('.dropdown-usuario').removeClass("dropdown-fadein");
        $(".badge-secondary").fadeOut();
        $(".dropdown-menu").fadeOut();
        $(".lookup-busca").fadeOut();
        e.stopPropagation();
    });
    /*Mostra o lookup da busca e oculta outras menus que estejam abertos*/
    // $(".select-busca").click(function() {
    //     var e=window.event||e;
    //     $(".dropdown-menu").fadeIn();
    //     $('.dropdown-usuario').removeClass("dropdown-fadein");
    //     $(".lookup-busca").fadeOut();
    //     e.stopPropagation();
    // });
    /*Oculta todos os menus abertos com um click fora*/
    $(document).click(function(){
        $('.dropdown-usuario').removeClass("dropdown-fadein");
        $('.dropdown-notificacao').removeClass("dropdown-fadein");
        $(".lista-notificacoes").fadeOut();
        $(".dropdown-menu").fadeOut();
        $(".lookup-busca").fadeOut();
    });

    $(".search-guiageek").on("keypress", function () {
        campoSearch = $(".search-guiageek");
        if(campoSearch.val().length > 2){
            $(".lookup-busca").fadeIn();
            carregarLookup($(".select-busca").text(), campoSearch.val());
        } else {
            $(".lookup-busca").fadeOut();
        }
    }).on("paste", function () {
        $(".lookup-busca").fadeIn();
        carregarLookup($(".select-busca").text(), $(".search-guiageek").val());
    });

    // $(".search-guiageek")

    /*Troca o tipo filtro de busca no select da busca*/
    $(".tipo-filtro").click(function(){
        $(".select-busca").html($(this).attr("name"));

    });
    /*FIM EVENTOS DAS OPÇOES DO MENU DO TOP*/

    /*EVENTOS DAS OPÇOES DE CADASTRO E LOGIN*/
    /*Esconde formulario de cadastro e link de login*/
    $(".formulario-cadastro").hide();
    $(".ja-tem-conta").hide();
    $(".use-email").click(function () {
        $(".container-login-cadastro img").hide("slow");
        $(".opcoes-cadastro").hide("slow");
        $(".formulario-cadastro").show("slow");
        $(".ja-tem-conta").show("slow");
    });
    /*FIM EVENTOS DAS OPÇOES DE CADASTRO E LOGIN*/

    /*EVENTOS PERFIL ANIME*/
    /*Troca de aba no perfil dos conteudos*/
    $(".link-aba-sumario").click(function () {
        trocarAbaPara("sumario");
    });
    $(".link-aba-episodios").click(function () {
        trocarAbaPara("episodios");
    });
    $(".link-aba-personagens").click(function () {
        trocarAbaPara("personagens");
    });

    /*Inicializa abas com a aba sumario*/
    $(".placeholder-aba").remove();
    $(".aba-sumario").show();

    $(".btn-assinar").click(function () {
        // $.get("index.php?acao=carregar_front", function(data, status){
        //
        // });
        $(this).addClass("dropdown-toggle");
        $(this).attr("data-toggle", "dropdown");
        if ("Assinar" === this.text()) {
            $(this).html("Estou lendo");
        }
    });
    /*Troca a situação do assitante no dropdown do btn assinar*/
    $(".situacao-assinante").click(function(){
        $(".btn-assinar").html($(this).attr("name"));
    });
});

/*Função chamada no evento de click para troca de abas nos perfis de conteudo*/
function trocarAbaPara(aba) {
    var classAbaAtiva;
    $(".nav-link").each(function (i) {
        if ($(this).hasClass("active")){
            $(this).removeClass("active");
            classAbaAtiva = $(this).attr("class");
        }
    });

    if(classAbaAtiva.indexOf("link-aba-") !== -1){
        var index = classAbaAtiva.indexOf(" ", classAbaAtiva.indexOf("link-aba-"));
        if(index === -1){
            classAbaAtiva = classAbaAtiva.substr(classAbaAtiva.indexOf("link-aba-"));
        }else {
            classAbaAtiva = classAbaAtiva.substr(classAbaAtiva.indexOf("link-aba-"), classAbaAtiva.indexOf(" "));
        }
    }
    classAbaAtiva = classAbaAtiva.split("-");
    classAbaAtiva = classAbaAtiva[classAbaAtiva.length-1];
    $(".link-aba-" + aba).addClass("active");
    $(".aba-" + classAbaAtiva).hide();
    $(".aba-" + aba).show();
}

function carregarLookup(tipoConteudo, search) {
    /*Trata string tipoConteudo*/
    tipoConteudo = $.trim(tipoConteudo);

    if (tipoConteudo === "Tudo"){
        var tagLiAnime = criaDocumentElementeLiLookup("Anime");
        $.get( "https://kitsu.io/api/edge/anime?page[limit]=3&page[offset]=0&filter[text]=" + search, function( data ) {
            for (i = 0; i < data.data.length; i++){
                var tagA = document.createElement("a");
                tagA.setAttribute("class", "item-busca");
                tagA.setAttribute("href", "http://" + document.domain + root + "/anime/" + data.data[i].id);
                var nomeConteudo = document.createTextNode(data.data[i].attributes.canonicalTitle);
                tagA.appendChild(nomeConteudo);
                tagLiAnime.append(tagA);
            }
            tagLiAnime.append(criaDocumentElementeDivVerMais("anime", search));
        });

        var tagLiManga = criaDocumentElementeLiLookup("Manga");
        $.get( "https://kitsu.io/api/edge/manga?page[limit]=3&page[offset]=0&filter[text]=" + search, function( data ) {
            for (i = 0; i < data.data.length; i++){
                var tagA = document.createElement("a");
                tagA.setAttribute("class", "item-busca");
                tagA.setAttribute("href", "http://" + document.domain + root + "/manga/" + data.data[i].id);
                var nomeConteudo = document.createTextNode(data.data[i].attributes.canonicalTitle);
                tagA.appendChild(nomeConteudo);
                tagLiManga.append(tagA);
            }
            tagLiManga.append(criaDocumentElementeDivVerMais("manga", search));
        });

        var tagLiHq = criaDocumentElementeLiLookup("HQs");
        var tagLiPessoa = criaDocumentElementeLiLookup("Pessoas");
        $(".lista-resultados-lookup").html([tagLiAnime, tagLiManga, tagLiHq, tagLiPessoa]);
        // $(".seccao-busca").append(tagLiHq).append(tagLiHq);
    } else {
        var tagLiGenerica = criaDocumentElementeLiLookup(tipoConteudo);
        $.get( "https://kitsu.io/api/edge/" + tipoConteudo.toLowerCase() + "?page[limit]=10&page[offset]=0&filter[text]=" + search, function( data ) {
            for (i = 0; i < data.data.length; i++){
                var tagA = document.createElement("a");
                tagA.setAttribute("class", "item-busca");
                tagA.setAttribute("href", "http://" + document.domain + root + "/" + tipoConteudo.toLowerCase() + "/" + data.data[i].id);
                var nomeConteudo = document.createTextNode(data.data[i].attributes.canonicalTitle);
                tagA.appendChild(nomeConteudo);
                tagLiGenerica.append(tagA);
            }
            tagLiGenerica.append(criaDocumentElementeDivVerMais(tipoConteudo, search));
        });
        $(".lista-resultados-lookup").html(tagLiGenerica);
    }
}

function criaDocumentElementeLiLookup(nomeSeccao) {
    var divTitulo = document.createElement("div");
    divTitulo.setAttribute("class", "container-classificacao-busca d-flex w-100 justify-content-between");
    var h4Titulo = document.createElement("h4");
    h4Titulo.setAttribute("class", "mb-1 card-title");
    var tituloSeccao = document.createTextNode(nomeSeccao);
    h4Titulo.appendChild(tituloSeccao);
    divTitulo.appendChild(h4Titulo);
    var tagLi = document.createElement("li");
    tagLi.setAttribute("class", "list-group-item list-group-item-action flex-column align-items-start seccao-busca");
    tagLi.appendChild(divTitulo);
    return tagLi;
}

function criaDocumentElementeDivVerMais(tipoConteudo, search) {
    var divVerMais = document.createElement("div");
    divVerMais.setAttribute("class", "w-100 container-ver-mais-busca");
    var tagAVermais = document.createElement("a");
    tagAVermais.setAttribute("class", "link-vermais");
    tagAVermais.setAttribute("href", "http://" + document.domain + root + "/galeria/" + tipoConteudo.toLowerCase() + "/a-z/" + search + "/1");
    tagAVermais.appendChild(document.createTextNode("Ver mais"));
    divVerMais.appendChild(tagAVermais);
    return divVerMais;
}