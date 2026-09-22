(() => {

    /*
     * ========================================================
     * SOLO EJECUTAR EN EL LOGIN
     * ========================================================
     *
     * Ruta:
     * http://grps-mldm.test/admin/login
     *
     * window.location.pathname devuelve:
     * /admin/login
     */

    if (
        window.location.pathname !== '/admin/login'
    ) {
        return;
    }


    /*
     * ========================================================
     * EVITAR DUPLICACIÓN
     * ========================================================
     */

    if (window.__grpFloatingBackground) {
        return;
    }

    window.__grpFloatingBackground = true;


    /*
     * ========================================================
     * CONFIGURACIÓN
     * ========================================================
     */

    const BALL_COUNT = 14;

    const MIN_SIZE = 35;

    const MAX_SIZE = 90;

    const MIN_SPEED = 0.8;

    const MAX_SPEED = 2.0;

    const MOUSE_RADIUS = 190;

    const MOUSE_FORCE = 0.12;

    const FRICTION = 0.995;

    const MAX_SPEED_LIMIT = 3.8;


    /*
     * ========================================================
     * CREAR CONTENEDOR
     * ========================================================
     */

    const background =
        document.createElement("div");

    background.className =
        "grp-floating-bg";


    /*
     * Insertar detrás del contenido
     */

    document.body.prepend(
        background
    );


    /*
     * ========================================================
     * MOUSE
     * ========================================================
     */

    const mouse = {

        x: -1000,

        y: -1000,

        active: false

    };


    document.addEventListener(
        "mousemove",
        (event) => {

            mouse.x =
                event.clientX;

            mouse.y =
                event.clientY;

            mouse.active = true;

        }
    );


    document.addEventListener(
        "mouseleave",
        () => {

            mouse.active = false;

        }
    );


    /*
     * ========================================================
     * ARRAY DE PELOTAS
     * ========================================================
     */

    const balls = [];


    /*
     * ========================================================
     * FUNCIÓN RANDOM
     * ========================================================
     */

    function random(min, max) {

        return Math.random() *
            (max - min) +
            min;

    }


    /*
     * ========================================================
     * CREAR PELOTAS
     * ========================================================
     */

    for (
        let i = 0;
        i < BALL_COUNT;
        i++
    ) {

        /*
         * Tamaño aleatorio
         */

        const size =
            random(
                MIN_SIZE,
                MAX_SIZE
            );


        /*
         * Crear elemento
         */

        const ball =
            document.createElement("div");


        ball.className =
            "grp-ball";


        /*
         * Variaciones de tamaño
         */

        if (
            size > 75
        ) {

            ball.classList.add(
                "grp-ball-large"
            );

        }


        if (
            size < 50
        ) {

            ball.classList.add(
                "grp-ball-small"
            );

        }


        /*
         * Tamaño
         */

        ball.style.width =
            `${size}px`;

        ball.style.height =
            `${size}px`;


        /*
         * ====================================================
         * POSICIÓN INICIAL
         * ====================================================
         */

        const x =
            random(
                0,
                Math.max(
                    1,
                    window.innerWidth -
                    size
                )
            );


        const y =
            random(
                0,
                Math.max(
                    1,
                    window.innerHeight -
                    size
                )
            );


        /*
         * ====================================================
         * DIRECCIÓN INICIAL
         * ====================================================
         */

        const angle =
            random(
                0,
                Math.PI * 2
            );


        /*
         * Velocidad inicial
         */

        const speed =
            random(
                MIN_SPEED,
                MAX_SPEED
            );


        let velocityX =
            Math.cos(angle) *
            speed;


        let velocityY =
            Math.sin(angle) *
            speed;


        /*
         * ====================================================
         * DATOS DE LA PELOTA
         * ====================================================
         */

        const ballData = {

            element: ball,

            x: x,

            y: y,

            size: size,

            velocityX: velocityX,

            velocityY: velocityY

        };


        /*
         * Guardar pelota
         */

        balls.push(
            ballData
        );


        /*
         * Agregar al fondo
         */

        background.appendChild(
            ball
        );

    }


    /*
     * ========================================================
     * CLICK
     * ========================================================
     *
     * Al hacer click las pelotas cercanas reciben
     * un impulso alejándose del cursor.
     */

    document.addEventListener(
        "click",
        (event) => {

            balls.forEach(
                (ball) => {

                    const centerX =
                        ball.x +
                        ball.size / 2;


                    const centerY =
                        ball.y +
                        ball.size / 2;


                    const dx =
                        centerX -
                        event.clientX;


                    const dy =
                        centerY -
                        event.clientY;


                    const distance =
                        Math.sqrt(
                            dx * dx +
                            dy * dy
                        );


                    /*
                     * Radio de influencia
                     */

                    if (
                        distance < 230
                    ) {

                        const directionX =
                            dx /
                            (distance || 1);


                        const directionY =
                            dy /
                            (distance || 1);


                        const force =
                            (
                                1 -
                                distance / 230
                            ) *
                            3.5;


                        /*
                         * Aplicar impulso
                         */

                        ball.velocityX +=
                            directionX *
                            force;


                        ball.velocityY +=
                            directionY *
                            force;


                        /*
                         * Animación visual
                         */

                        ball.element.classList
                            .remove(
                                "grp-click-effect"
                            );


                        void ball.element
                            .offsetWidth;


                        ball.element.classList
                            .add(
                                "grp-click-effect"
                            );

                    }

                }
            );

        }
    );


    /*
     * ========================================================
     * COLISIÓN ENTRE PELOTAS
     * ========================================================
     */

    function handleBallCollisions() {

        for (
            let i = 0;
            i < balls.length;
            i++
        ) {

            for (
                let j = i + 1;
                j < balls.length;
                j++
            ) {

                const ballA =
                    balls[i];


                const ballB =
                    balls[j];


                /*
                 * Centros
                 */

                const centerAX =
                    ballA.x +
                    ballA.size / 2;


                const centerAY =
                    ballA.y +
                    ballA.size / 2;


                const centerBX =
                    ballB.x +
                    ballB.size / 2;


                const centerBY =
                    ballB.y +
                    ballB.size / 2;


                /*
                 * Distancia
                 */

                const dx =
                    centerBX -
                    centerAX;


                const dy =
                    centerBY -
                    centerAY;


                const distance =
                    Math.sqrt(
                        dx * dx +
                        dy * dy
                    );


                /*
                 * Distancia mínima
                 */

                const minimumDistance =
                    (
                        ballA.size +
                        ballB.size
                    ) / 2;


                /*
                 * =================================================
                 * COLISIÓN
                 * =================================================
                 */

                if (
                    distance > 0 &&
                    distance < minimumDistance
                ) {

                    /*
                     * Vector normal
                     */

                    const normalX =
                        dx /
                        distance;


                    const normalY =
                        dy /
                        distance;


                    /*
                     * Solapamiento
                     */

                    const overlap =
                        minimumDistance -
                        distance;


                    /*
                     * Separar las pelotas
                     */

                    ballA.x -=
                        normalX *
                        overlap /
                        2;


                    ballA.y -=
                        normalY *
                        overlap /
                        2;


                    ballB.x +=
                        normalX *
                        overlap /
                        2;


                    ballB.y +=
                        normalY *
                        overlap /
                        2;


                    /*
                     * Velocidad relativa
                     */

                    const relativeVelocityX =
                        ballA.velocityX -
                        ballB.velocityX;


                    const relativeVelocityY =
                        ballA.velocityY -
                        ballB.velocityY;


                    /*
                     * Velocidad en dirección
                     * de la colisión
                     */

                    const velocityAlongNormal =
                        relativeVelocityX *
                        normalX +
                        relativeVelocityY *
                        normalY;


                    /*
                     * Si ya se están separando,
                     * no hacer nada.
                     */

                    if (
                        velocityAlongNormal > 0
                    ) {

                        continue;

                    }


                    /*
                     * Elasticidad
                     */

                    const restitution =
                        0.95;


                    /*
                     * Impulso
                     */

                    const impulse =
                        -(
                            1 +
                            restitution
                        ) *
                        velocityAlongNormal /
                        2;


                    const impulseX =
                        impulse *
                        normalX;


                    const impulseY =
                        impulse *
                        normalY;


                    /*
                     * Aplicar impulso
                     */

                    ballA.velocityX +=
                        impulseX;


                    ballA.velocityY +=
                        impulseY;


                    ballB.velocityX -=
                        impulseX;


                    ballB.velocityY -=
                        impulseY;

                }

            }

        }

    }


    /*
     * ========================================================
     * ANIMACIÓN
     * ========================================================
     */

    function animate() {


        /*
         * ====================================================
         * MOVER PELOTAS
         * ====================================================
         */

        balls.forEach(
            (ball) => {

                ball.x +=
                    ball.velocityX;


                ball.y +=
                    ball.velocityY;


                /*
                 * =================================================
                 * INTERACCIÓN CON MOUSE
                 * =================================================
                 */

                if (
                    mouse.active
                ) {

                    const centerX =
                        ball.x +
                        ball.size / 2;


                    const centerY =
                        ball.y +
                        ball.size / 2;


                    const dx =
                        centerX -
                        mouse.x;


                    const dy =
                        centerY -
                        mouse.y;


                    const distance =
                        Math.sqrt(
                            dx * dx +
                            dy * dy
                        );


                    /*
                     * Mouse cerca
                     */

                    if (
                        distance <
                        MOUSE_RADIUS
                    ) {

                        /*
                         * Dirección de escape
                         */

                        const directionX =
                            dx /
                            (distance || 1);


                        const directionY =
                            dy /
                            (distance || 1);


                        /*
                         * Intensidad
                         */

                        const intensity =
                            (
                                MOUSE_RADIUS -
                                distance
                            ) /
                            MOUSE_RADIUS;


                        /*
                         * Aplicar fuerza
                         */

                        ball.velocityX +=
                            directionX *
                            intensity *
                            MOUSE_FORCE;


                        ball.velocityY +=
                            directionY *
                            intensity *
                            MOUSE_FORCE;


                        /*
                         * Efecto visual
                         */

                        ball.element.classList
                            .add(
                                "grp-near-mouse"
                            );

                    } else {

                        ball.element.classList
                            .remove(
                                "grp-near-mouse"
                            );

                    }

                }


                /*
                 * =================================================
                 * FRENADO
                 * =================================================
                 */

                ball.velocityX *=
                    FRICTION;


                ball.velocityY *=
                    FRICTION;


                /*
                 * =================================================
                 * VELOCIDAD ACTUAL
                 * =================================================
                 */

                let currentSpeed =
                    Math.sqrt(
                        ball.velocityX *
                        ball.velocityX +

                        ball.velocityY *
                        ball.velocityY
                    );


                /*
                 * =================================================
                 * VELOCIDAD MÍNIMA
                 * =================================================
                 */

                if (
                    currentSpeed <
                    MIN_SPEED
                ) {

                    const angle =
                        Math.atan2(
                            ball.velocityY,
                            ball.velocityX
                        );


                    /*
                     * Si por alguna razón
                     * quedó completamente quieta
                     */

                    if (
                        currentSpeed === 0
                    ) {

                        ball.velocityX =
                            Math.cos(
                                random(
                                    0,
                                    Math.PI * 2
                                )
                            ) *
                            MIN_SPEED;


                        ball.velocityY =
                            Math.sin(
                                random(
                                    0,
                                    Math.PI * 2
                                )
                            ) *
                            MIN_SPEED;

                    } else {

                        ball.velocityX =
                            Math.cos(angle) *
                            MIN_SPEED;


                        ball.velocityY =
                            Math.sin(angle) *
                            MIN_SPEED;

                    }

                }


                /*
                 * =================================================
                 * RECALCULAR VELOCIDAD
                 * =================================================
                 */

                currentSpeed =
                    Math.sqrt(
                        ball.velocityX *
                        ball.velocityX +

                        ball.velocityY *
                        ball.velocityY
                    );


                /*
                 * =================================================
                 * VELOCIDAD MÁXIMA
                 * =================================================
                 */

                if (
                    currentSpeed >
                    MAX_SPEED_LIMIT
                ) {

                    ball.velocityX =
                        (
                            ball.velocityX /
                            currentSpeed
                        ) *
                        MAX_SPEED_LIMIT;


                    ball.velocityY =
                        (
                            ball.velocityY /
                            currentSpeed
                        ) *
                        MAX_SPEED_LIMIT;

                }


                /*
                 * =================================================
                 * REBOTE IZQUIERDO
                 * =================================================
                 */

                if (
                    ball.x <= 0
                ) {

                    ball.x = 0;


                    ball.velocityX =
                        Math.abs(
                            ball.velocityX
                        );

                }


                /*
                 * =================================================
                 * REBOTE DERECHO
                 * =================================================
                 */

                if (
                    ball.x +
                    ball.size >=
                    window.innerWidth
                ) {

                    ball.x =
                        window.innerWidth -
                        ball.size;


                    ball.velocityX =
                        -Math.abs(
                            ball.velocityX
                        );

                }


                /*
                 * =================================================
                 * REBOTE SUPERIOR
                 * =================================================
                 */

                if (
                    ball.y <= 0
                ) {

                    ball.y = 0;


                    ball.velocityY =
                        Math.abs(
                            ball.velocityY
                        );

                }


                /*
                 * =================================================
                 * REBOTE INFERIOR
                 * =================================================
                 */

                if (
                    ball.y +
                    ball.size >=
                    window.innerHeight
                ) {

                    ball.y =
                        window.innerHeight -
                        ball.size;


                    ball.velocityY =
                        -Math.abs(
                            ball.velocityY
                        );

                }

            }
        );


        /*
         * ====================================================
         * COLISIONES ENTRE PELOTAS
         * ====================================================
         */

        handleBallCollisions();


        /*
         * ====================================================
         * ACTUALIZAR POSICIONES
         * ====================================================
         */

        balls.forEach(
            (ball) => {

                ball.element.style.transform =
                    `translate3d(
                        ${ball.x}px,
                        ${ball.y}px,
                        0
                    )`;

            }
        );


        /*
         * ====================================================
         * SIGUIENTE FRAME
         * ====================================================
         */

        requestAnimationFrame(
            animate
        );

    }


    /*
     * ========================================================
     * INICIAR ANIMACIÓN
     * ========================================================
     */

    animate();


    /*
     * ========================================================
     * RESPONSIVE
     * ========================================================
     */

    window.addEventListener(
        "resize",
        () => {

            balls.forEach(
                (ball) => {

                    ball.x =
                        Math.min(
                            ball.x,
                            Math.max(
                                0,
                                window.innerWidth -
                                ball.size
                            )
                        );


                    ball.y =
                        Math.min(
                            ball.y,
                            Math.max(
                                0,
                                window.innerHeight -
                                ball.size
                            )
                        );

                }
            );

        }
    );


})();