# README - Teste de Avaliação: Software para Processamento de Dados de Movimentações de Conta Corrente

## Descrição do Projeto

Este projeto é um teste de avaliação que visa desenvolver e entregar um software funcional para o processamento de dados das movimentações de conta corrente dos associados da **Sicredi Pioneira RS**. A aplicação deve ser capaz de abranger todas as movimentações (créditos e débitos) realizadas pelos associados em um período determinado.

A principal finalidade da aplicação é permitir que o usuário transforme dados brutos em informações estratégicas, de modo a apoiar a continuidade do negócio. Para alcançar esse objetivo, o software será responsável por:

- Realizar a carga das bases de dados brutas que contêm as movimentações bancárias dos associados.
- Processar esses dados para identificar padrões e comportamentos de movimentação financeira.
- Gerar sumarizações e métricas detalhadas sobre o comportamento dos associados, como totais de crédito e débito, frequências de transações, e outros indicadores financeiros relevantes.

## Funcionalidades

O sistema deverá oferecer, ao mínimo, as seguintes funcionalidades:

1. **Carga de Dados Brutos**: Importação dos dados das movimentações financeiras dos associados.
2. **Processamento de Movimentações**: Análise e cálculo de métricas a partir das movimentações (créditos e débitos).
3. **Geração de Relatórios**: Exibição de sumarizações e métricas, como:
   - Total de créditos e débitos por associado.
   - Média de movimentações.
   - Identificação de padrões de comportamento financeiro.
4. **Interface Intuitiva**: O usuário deve ser capaz de realizar essas operações de forma simples e rápida, sem a necessidade de interações complexas.

## Objetivo

O objetivo deste projeto é demonstrar a capacidade de transformar dados financeiros em informações úteis para a gestão do negócio, possibilitando aos gestores da **Sicredi Pioneira RS** uma visão mais clara do comportamento financeiro dos associados e permitindo decisões mais informadas e assertivas.

## Tecnologias Utilizadas

Este projeto foi desenvolvido utilizando as seguintes tecnologias:

- **Laravel 11**: Framework PHP utilizado para o desenvolvimento do backend da aplicação, garantindo uma estrutura robusta e escalável.
- **Vue.js**: Framework JavaScript para construção da interface do usuário, proporcionando uma experiência dinâmica e interativa.
- **Docker**: Containerização da aplicação para facilitar o ambiente de desenvolvimento, teste e deploy.
- **Axios**: Biblioteca JavaScript utilizada para realizar requisições HTTP de forma eficiente e simplificada, permitindo a comunicação entre o frontend e o backend.
- **JSZip**: Biblioteca JavaScript utilizada para criar e manipular arquivos ZIP, especialmente para o processamento e exportação de grandes volumes de dados em formato compactado.
- **Regex101**: Ferramenta online utilizada para testar e validar expressões regulares (regex), aplicadas no processamento e filtragem de dados.
- **Chart.js**: Biblioteca JavaScript utilizada para a criação de gráficos interativos, visualizando as métricas e informações geradas a partir dos dados processados.


