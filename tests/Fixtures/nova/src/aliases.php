<?php

namespace Laravel\Nova;

class_alias(StaticallyUselessClass::class, Nova::class);

class_alias(UselessServiceProvider::class, NovaServiceProvider::class);
class_alias(UselessServiceProvider::class, NovaApplicationServiceProvider::class);

class_alias(StaticallyUselessClass::class, Actions\Action::class);

class_alias(StaticallyUselessClass::class, Filters\Filter::class);

class_alias(StaticallyUselessClass::class, Http\Requests\NovaRequest::class);

class_alias(StaticallyUselessClass::class, Metrics\Partition::class);
class_alias(StaticallyUselessClass::class, Metrics\Trend::class);
class_alias(StaticallyUselessClass::class, Metrics\Value::class);

class_alias(StaticallyUselessClass::class, Panel::class);
class_alias(StaticallyUselessClass::class, Resource::class);

class_alias(StaticallyUselessClass::class, Fields\Field::class);

