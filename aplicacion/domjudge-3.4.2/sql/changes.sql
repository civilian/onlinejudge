-- Volcado de datos para la tabla `contest`
--
INSERT INTO `contest` (`cid`, `contestname`, `activatetime`, `starttime`, `freezetime`, `endtime`, `unfreezetime`, `activatetime_string`, `freezetime_string`, `endtime_string`, `unfreezetime_string`, `enabled`) VALUES
(2, 'Demo contest', '2012-01-01 11:30:00', '2012-01-01 12:00:00', '2014-01-01 16:00:00', '2099-01-01 17:00:00', '2099-01-01 17:30:00', '2012-01-01 11:30:00', '2014-01-01 16:00:00', '2099-01-01 17:00:00', '2099-01-01 17:30:00', 1);

ALTER TABLE  `testcase` ADD UNIQUE (
`mdl_testcaseid`
);

UPDATE  `domjudge`.`configuration` 
SET  `value` =  '{"presentation-error":"presentation-error"}' 
WHERE  `configuration`.`name` = 'results_remap';

ALTER TABLE  `documentation_style` ADD  `owner` INT( 8 ) NOT NULL DEFAULT  '-1' COMMENT  'professor responsible of this style' AFTER  `langid` ;

UPDATE  `domjudge`.`indentation_style` SET  `sample` =  'static char *
concat (char *s1, char *s2)
{
  while (x == y)
    {
      something ();
      somethingelse ();
    }
  finalthing ();
}' WHERE  `indentation_style`.`indentation_styleid` =1;


