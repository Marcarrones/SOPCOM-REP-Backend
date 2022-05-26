INSERT INTO method_element_type (name) VALUES ('tool');
INSERT INTO method_element_type (name) VALUES ('artefact');
INSERT INTO method_element_type (name) VALUES ('activity');
INSERT INTO method_element_type (name) VALUES ('role');
#################################################################################################
INSERT INTO struct_rel_type (id, name) VALUES (1, 'specialization');
INSERT INTO struct_rel_type (id, name) VALUES (2, 'aggregation');
#################################################################################################
INSERT INTO activity_rel_type (id, name) VALUES (1, 'start-start');
INSERT INTO activity_rel_type (id, name) VALUES (2, 'end-start');
INSERT INTO activity_rel_type (id, name) VALUES (3, 'exclusion');
#################################################################################################
INSERT INTO artefact_rel_type (id, name) VALUES (1, 'constraint');
INSERT INTO artefact_rel_type (id, name) VALUES (2, 'in-sync');