var root = "/";
// root = "/guiageeks";

function CarregaConteudo(idConteudo, tipoConteudo) {

    /*PROVISORIAMENTE SO PRA EDIÇÃO DAS SINOPESES*/
    $("#anime").attr('value', idConteudo);


    /*
    * Esta func é disparada quando o request é concluido e retorna os dados
    * os dados então são filtrados e mandados para a func personalizaTela(data)
    * então são carregados na tela os dados do conteudo especificado pelo idConteudo
    * */
    var successFunction = function(data) {
        personalizaTela(JSON.parse(data));
    };

    /*
    * Requisição a API da kitsu para obiter as informações do conteudo solicitado
    * */
    var promise = $.ajax({
        type: 'GET',
        contentType: "application/json",
        url: "http://" + document.domain + "/" + root + tipoConteudo + "?acao=carregar_front&id=" + idConteudo,
        success: function(data){
            successFunction(data);
        },
        error: function(){
            // location.reload();
        }
    });

    var personalizaTela = function (data) {
        /*
        * Remove todas animações de placeholder
        * */
        $(".placeholder-animado").removeClass("placeholder-animado");

        /*
        * Configura Cover top
        * */
        $(".cover-top")
        .css("background", "url(" + data.cover + ") no-repeat 0")
        .css("background-size", "cover");

        /*
        * Coloca imagem poster
        * */
        $(".sidebar-poster > img").attr("src", data.poster);

        /*
        * Titulo Sumário
        * */
        if (tipoConteudo === "anime" || tipoConteudo === "manga"){
            $(".titulo-aba-sumario").html(data.titulo_ingles);
        }else {
            $(".titulo-aba-sumario").html(data.nome);

        }

        /*
        * Carrega sinopise ou descrição
        * */
        if (tipoConteudo === "anime" || tipoConteudo === "manga"){
            $(".sinopse-conteudo").html(data.sinopse);
        }else {
            $(".sinopse-conteudo").html(data.descricao);

        }

        /*
        * Cria e retorna elemento HTML no formato li > strong > textoDetalhe, colocar em Detalhes contuedo
        * */
        var criaCampoDetalhe = function (labelDetalhe, textoDetalhe) {
            var tagLi = document.createElement("li");
            var tagStrong = document.createElement("strong");
            tagStrong.innerHTML = labelDetalhe;
            tagLi.append(tagStrong);
            tagLi.append(textoDetalhe);
            return tagLi;
        };

        /*
        * Carrega detalhes do conteudo
        * */
        $(".atributos-conteudo")
        .append(data.titulo_ingles ? criaCampoDetalhe("Inglês", data.titulo_ingles) : "")
        .append(data.titulo_romanizado ? criaCampoDetalhe("Romanizado", data.titulo_romanizado) : "")
        .append(data.titulo_japones ? criaCampoDetalhe("Japonês", data.titulo_japones) : "")
        .append(data.generos ? criaCampoDetalhe("Géneros", data.generos) : "")
        .append(data.status ? criaCampoDetalhe("Status", data.status) : "")
        .append(data.data_inicio ? criaCampoDetalhe("Foi ao ar", data.data_inicio + (data.data_fim ? " a " + data.data_fim : "")) : "")
        .append(data.indicacao ? criaCampoDetalhe("Indicação", data.indicacao) : "")
        .append(data.qtd_capitulos ? criaCampoDetalhe("Capítulos", data.qtd_capitulos) : "")
        .append(data.qtd_volumes ? criaCampoDetalhe("Volumes", data.qtd_volumes) : "")
        // Informações de HQs
        .append(data.nome ? criaCampoDetalhe("Nome", data.nome) : "")
        .append(data.editora ? criaCampoDetalhe("Editora", data.editora) : "")
        .append(data.ano ? criaCampoDetalhe("Ano", data.ano) : "");

        /*
        * carrega thumbnail youtube
        * */
        if (data.video_youtube){
            $(".thumbnail-trailer")
                .css("background", "url(https://img.youtube.com/vi/" + data.video_youtube + "/mqdefault.jpg) no-repeat 0")
                .css("background-size", "cover");
        }
        if (data.volumes){
            console.log(data.volumes);
            var i = 0;
            $( ".poster-volume" ).each(function( index ) {
                if (data.volumes[i]){
                    $(this).attr("src",data.volumes[i]);
                    i++;
                }else {
                    $(this).remove();
                }
            });
        }

        if (data.conteudos_relacionados){
            $(".poster-conteudo-relacionado a").each(function (i) {
                $(this).attr("href", $(this).attr("href") + "/" + tipoConteudo + "/" + data.conteudos_relacionados[i].id_anime);
                $(this).children().attr("src", data.conteudos_relacionados[i].poster);
            });
        } else {
            $(".titulo-conteudo-relacionado").remove();
            $(".row-conteudo-relacionado").remove();
        }
    }

}
$(document).ready(function(){
    /*Obter id do anime atravez da URL para carregar seu respectivo perfil*/
    var id_anime = window.location.href;
    id_anime = id_anime.split("/");
    console.log(id_anime);
    var tipo_conteudo = id_anime[3];
    id_anime = id_anime[4];
    new CarregaConteudo(id_anime, tipo_conteudo);
});