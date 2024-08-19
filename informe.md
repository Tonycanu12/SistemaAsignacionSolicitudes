## Informe de Diseño y Algoritmos de Asignación

### Decisiones de Diseño
El servicio fue diseñado para asignar usuarios a solicitudes de acuerdo a diferentes criterios, lo que proporciona flexibilidad y adaptabilidad en la gestión de tareas. Para abordar las necesidades de asignación, se implementaron cuatro algoritmos: Aleatorio, Secuencial, Equitativo y Directo. Cada uno sigue un conjunto de pasos específicos para asegurar que las solicitudes se asignen de manera justa y eficiente.

### Algoritmos de Asignación

#### Asignación Aleatoria (algorithmRandom)
* **Propósito:** Asignar un usuario al azar a una solicitud específica, basado en un rol predefinido.
* **Proceso:**
-   **Validación de Parámetros:** Se asegura de que los parámetros `role` (rol del usuario) y `request_id` (identificador de la solicitud) estén presentes.
-   **Obtención de Usuarios:** Se obtiene una lista de usuarios que tienen el rol especificado.
-   **Validación de Usuarios:** Se verifica que la lista de usuarios no esté vacía. Si lo está, se lanza una excepción.
-   **Validación de Solicitud:** Se comprueba que la solicitud con `request_id` exista y que su estado sea `pendiente`.
-   **Selección Aleatoria:** Se selecciona un usuario al azar de la lista de usuarios obtenidos.
-   **Asignación:** Se asigna la solicitud al usuario seleccionado.
-   **Actualización de Estado:** Se actualiza el estado de la solicitud a `asignado` para indicar que ha sido gestionada.

#### Asignación Secuencial (algorithSequential)
* **Propósito:** Distribuir las solicitudes entre los usuarios de manera secuencial.
* **Proceso:**
-   **Obtención de Usuarios:** Se obtiene una lista de usuarios con el rol especificado.
-   **Validación de Usuarios:** Se asegura de que la lista de usuarios no esté vacía.
-   **Obtención de Solicitudes Pendientes:** Se recuperan todas las solicitudes que tienen el estado `pendiente`.
-   **Validación de Solicitudes:** Se verifica que haya solicitudes pendientes. Si no las hay, se lanza una excepción.
-   **Asignación Secuencial:** Las solicitudes pendientes se asignan a los usuarios de forma secuencial. Por ejemplo, la primera solicitud se asigna al primer usuario, la segunda al segundo usuario, y así sucesivamente. Si se llega al final de la lista de usuarios, el ciclo se reinicia desde el primer usuario.
-   **Actualización de Estado:** Después de asignar cada solicitud, su estado se actualiza a `asignado`.

#### Asignación Equitativa (algorithEquity)
* **Propósito:** Asegurar una distribución equitativa de las solicitudes entre los usuarios.
* **Proceso:**
-   **Obtención de Usuarios:** Se obtiene una lista de usuarios con el rol especificado.
-   **Validación de Usuarios:** Se verifica que la lista no esté vacía.
-   **Obtención de Solicitudes Pendientes:** Se recuperan todas las solicitudes con estado `pendiente`.
-   **Validación de Solicitudes:** Se asegura de que haya solicitudes pendientes.
-   **Verificación de Asignaciones Anteriores:** Se recupera un historial de cuántas solicitudes ha gestionado cada usuario previamente.
-   **Distribución Equitativa:** Para cada solicitud pendiente, se asigna al usuario que tenga la menor cantidad de asignaciones previas, buscando balancear la carga de trabajo entre todos.
-   **Actualización de Estado:** Una vez asignada la solicitud, su estado se actualiza a `asignado`.

#### Asignación Directa (algorithDirect)
* **Propósito:** Asignar una solicitud directamente a un usuario específico.
* **Proceso:**
*  **Validación de Parámetros:** Se asegura de que los parámetros `role`, `user_id` (identificador del usuario) y `request_id` estén presentes.
-   **Verificación del Usuario:** Se verifica que el usuario con `user_id` existe y que su rol coincide con el especificado.
-   **Validación de la Solicitud:** Se verifica que la solicitud con `request_id` existe y que su estado es `pendiente`.
-   **Asignación Directa:** La solicitud se asigna directamente al usuario especificado.
-   **Actualización de Estado:** Después de la asignación, el estado de la solicitud se actualiza a `asignado`.

### Conclusión
Estos algoritmos de asignación permiten gestionar las solicitudes de manera flexible, adaptándose a diferentes necesidades de distribución de trabajo. La implementación de múltiples enfoques asegura que las tareas se asignen de manera justa y eficiente, ya sea distribuyéndolas al azar, en secuencia, de manera equitativa o directamente a un usuario específico.
