## A pasta `src/v1`

Esta pasta contém seu ponto de entrada `openapi.yaml`.

Esse arquivo contém referências a toda a definição da API.

Aqui estão algumas seções para prestar atenção:

- **info**: As informações da API, como título, versão, descrição, logo, contato e licença.
- **servers**: A lista dos servidores localmente.
- **tags**: Organização de cada operação. Isso ajuda manter a documentação mais organizada. Cada tag pode ter uma descrição, e esta é usada na seção nos documentos de referência.
- **components**: Os componentes da API, é separado por `securitySchemas`, `schemas`, `requestBodies` e `responses`.
- [**paths**](paths/README.md): Define cada operação, endpoint


