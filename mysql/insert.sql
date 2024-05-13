INSERT INTO repository_status (id, name) VALUES (0, 'Draft');
INSERT INTO repository_status (id, name) VALUES (1, 'Public');
#################################################################################################
INSERT INTO repository (id, name, description, status) VALUES ('Repository_1', 'TestRepository', 'Example Repository', (SELECT id FROM repository_status WHERE name = 'Draft'));
INSERT INTO repository (id, name, description, status) VALUES ('Repository_2', 'TestRepository2', 'Example Repository', (SELECT id FROM repository_status WHERE name = 'Draft'));
#################################################################################################
INSERT INTO method_element_type (name) VALUES ('tool');
INSERT INTO method_element_type (name) VALUES ('artefact');
INSERT INTO method_element_type (name) VALUES ('activity');
INSERT INTO method_element_type (name) VALUES ('role');
#################################################################################################
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Tool-Jira-01', 'Jira', 0, NULL, NULL, (SELECT id FROM method_element_type WHERE name = 'tool'), 'Repository_1');
INSERT INTO tool (id) VALUES ('Tool-Jira-01');
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Tool-GoFo-01', 'Google Forms', 0, NULL, NULL, (SELECT id FROM method_element_type WHERE name = 'tool'), 'Repository_1');
INSERT INTO tool (id) VALUES ('Tool-GoFo-01');
#################################################################################################
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Art-LiSt-01', 'List of stakeholders', 0, NULL, NULL, (SELECT id FROM method_element_type WHERE name = 'artefact'), 'Repository_1');
INSERT INTO artefact (id) VALUES ('Art-LiSt-01');
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Art-LiCt-01', 'List of constraints', 0, NULL, NULL, (SELECT id FROM method_element_type WHERE name = 'artefact'), 'Repository_1');
INSERT INTO artefact (id) VALUES ('Art-LiCt-01');
INSERT INTO method_element (id, name, abstract, description, figure, type, repository)
    VALUES ('Art-LiGo-01', 'List of goals', 0, NULL, NULL, (SELECT id FROM method_element_type WHERE name = 'artefact'), 'Repository_1');
INSERT INTO artefact (id) VALUES ('Art-LiGo-01');
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Art-LiRq-01', 'List of requirements', 0, NULL, NULL, (SELECT id FROM method_element_type WHERE name = 'artefact'), 'Repository_1');
INSERT INTO artefact (id) VALUES ('Art-LiRq-01');
#################################################################################################
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Act-ElRq-01', 'Elicit Requirements', 1, NULL, NULL, (SELECT id FROM method_element_type WHERE name = 'activity'), 'Repository_1');
INSERT INTO activity (id) VALUES ('Act-ElRq-01');
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Act-ElRq-02', 'Elicit Requirements from workshop session', 0, 'Select stakeholders to participate in the workshop, schedule the workshop session, etc (not included for brevity reasons)', NULL, (SELECT id FROM method_element_type WHERE name = 'activity'), 'Repository_1');
INSERT INTO activity (id) VALUES ('Act-ElRq-02');
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Act-ElRq-03', 'Elicit Requirements from survey', 0, 'Elaborate survey, select stakeholders to whom send the survey, obtain responses and extract requirements from responses.', NULL, (SELECT id FROM method_element_type WHERE name = 'activity'), 'Repository_1');
INSERT INTO activity (id) VALUES ('Act-ElRq-03');
#################################################################################################
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Rol-RqEn-01', 'Requirements Engineer', 0, 'Organizes and supervises the elicitation of the requirements', NULL, (SELECT id FROM method_element_type WHERE name = 'role'), 'Repository_1');
INSERT INTO [role] (id) VALUES ('Rol-RqEn-01');
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Rol-SeSt-01', 'Set of stakeholders', 0, 'Provides information useful for requirements elicitation', NULL, (SELECT id FROM method_element_type WHERE name = 'role'), 'Repository_1');
INSERT INTO [role] (id) VALUES ('Rol-SeSt-01');
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Rol-WoFc-01', 'Workshop Facilitator', 0, 'Organizes and conducts the workshop', NULL, (SELECT id FROM method_element_type WHERE name = 'role'), 'Repository_1');
INSERT INTO [role] (id) VALUES ('Rol-WoFc-01');
INSERT INTO method_element (id, name, abstract, description, figure, type, repository) 
    VALUES ('Rol-SuFc-01', 'Survey Facilitator', 0, 'Sends surveys and obtains results ', NULL, (SELECT id FROM method_element_type WHERE name = 'role'), 'Repository_1');
INSERT INTO [role] (id) VALUES ('Rol-SuFc-01');
#################################################################################################
INSERT INTO map (id, name, repository, pruebas) 
	VALUES ('Map_1', 'Test_Map', 'Repository_1', '[{"x": -100.0, "y": 0.0, "id": "Start", "name": "Start"}, {"x": 200.0, "y": 0.0, "id": "Stop", "name": "Stop"}]');
#################################################################################################
INSERT INTO goal (name, x, y, map) VALUES ('Start','-100','0.0','Map_1');
INSERT INTO goal (name, x, y, map) VALUES ('Stop','200','0.0','Map_1');
#################################################################################################
INSERT INTO strategy (id, x, y, name, goal_src, goal_tgt)
    VALUES ('S_StartToStop', '0.0', '0.0', 'StartToStop', (SELECT id FROM goal WHERE name = 'Start' AND map = 'Map_1' LIMIT 1),(SELECT id FROM goal WHERE name = 'Stop' AND map = 'Map_1' LIMIT 1) );
#################################################################################################
INSERT INTO method_chunk (id, name, description, activity, intention, strategy, repository) VALUES ('Chu-ReqEli-01', 'Requirements Elicitation', 'This activity applies some selected technique to elicit a list of requirements from a set of stakeholders', 'Act-ElRq-01', 1, null, 'Repository_1');
INSERT INTO method_chunk (id, name, description, activity, intention, strategy, repository) VALUES ('Chu-ReqEli-02', 'Requirements Elicitation from workshop session', 'This activity applies a workshop session technique to elicit a list of requirements', 'Act-ElRq-02', 1, null, 'Repository_1');
INSERT INTO method_chunk (id, name, description, activity, intention, strategy, repository) VALUES ('Chu-ReqEli-03', 'Requirements Elicitation from survey', 'This activity applies a survey technique to elicit a list of requirements', 'Act-ElRq-03', 1, null, 'Repository_1');
#################################################################################################
INSERT INTO method_chunk_uses_tool (idMC, idME) VALUES ('Chu-ReqEli-01', 'Tool-Jira-01');
INSERT INTO method_chunk_uses_tool (idMC, idME) VALUES ('Chu-ReqEli-03', 'Tool-GoFo-01');
#################################################################################################
INSERT INTO method_chunk_produces_artefact (idMC, idME) VALUES ('Chu-ReqEli-01', 'Art-LiRq-01');
#################################################################################################
INSERT INTO method_chunk_consumes_artefact (idMC, idME) VALUES ('Chu-ReqEli-01', 'Art-LiSt-01');
INSERT INTO method_chunk_consumes_artefact (idMC, idME) VALUES ('Chu-ReqEli-01', 'Art-LiCt-01');
INSERT INTO method_chunk_consumes_artefact (idMC, idME) VALUES ('Chu-ReqEli-01', 'Art-LiGo-01');
#################################################################################################
INSERT INTO method_chunk_includes_role (idMC, idME, isSet) VALUES ('Chu-ReqEli-01', 'Rol-RqEn-01', 0);
INSERT INTO method_chunk_includes_role (idMC, idME, isSet) VALUES ('Chu-ReqEli-01', 'Rol-SeSt-01', 1);
INSERT INTO method_chunk_includes_role (idMC, idME, isSet) VALUES ('Chu-ReqEli-02', 'Rol-WoFc-01', 0);
INSERT INTO method_chunk_includes_role (idMC, idME, isSet) VALUES ('Chu-ReqEli-03', 'Rol-SuFc-01', 0);
#################################################################################################
INSERT INTO criterion (id, name, repository) VALUES (1, 'Stakeholders size', 'Repository_1');
INSERT INTO criterion (id, name, repository) VALUES (2, 'Stakeholders expertise', 'Repository_1');
#################################################################################################
INSERT INTO value (id, name, criterion) VALUES (1, 'High', 1);
INSERT INTO value (id, name, criterion) VALUES (2, 'Medium', 1);
INSERT INTO value (id, name, criterion) VALUES (3, 'Small', 1);
INSERT INTO value (id, name, criterion) VALUES (4, 'High', 2);
INSERT INTO value (id, name, criterion) VALUES (5, 'Medium', 2);
INSERT INTO value (id, name, criterion) VALUES (6, 'Basic', 2);
#################################################################################################
INSERT INTO assign_method_chunk (idMC, criterion) VALUES ('Chu-ReqEli-02', 1);
INSERT INTO assign_method_chunk (idMC, criterion) VALUES ('Chu-ReqEli-03', 1);
INSERT INTO assign_method_chunk (idMC, criterion) VALUES ('Chu-ReqEli-03', 2);
#################################################################################################
INSERT INTO assign_method_chunk_value (idMC, criterion, value) VALUES ('Chu-ReqEli-02', 1, 1);
INSERT INTO assign_method_chunk_value (idMC, criterion, value) VALUES ('Chu-ReqEli-03', 1, 1);
INSERT INTO assign_method_chunk_value (idMC, criterion, value) VALUES ('Chu-ReqEli-03', 2, 1);
INSERT INTO assign_method_chunk_value (idMC, criterion, value) VALUES ('Chu-ReqEli-03', 2, 2);
INSERT INTO assign_method_chunk_value (idMC, criterion, value) VALUES ('Chu-ReqEli-03', 2, 3);
#################################################################################################
INSERT INTO me_rel (fromME, toME) VALUES ('Act-ElRq-02', 'Act-ElRq-01');
INSERT INTO me_rel (fromME, toME) VALUES ('Act-ElRq-03', 'Act-ElRq-01');
#################################################################################################
INSERT INTO struct_rel_type (id, name) VALUES (1, 'specialization');
INSERT INTO struct_rel_type (id, name) VALUES (2, 'aggregation');
#################################################################################################
INSERT INTO me_struct_rel (fromME, toMe, rel) VALUES ('Act-ElRq-02', 'Act-ElRq-01', 1);
INSERT INTO me_struct_rel (fromME, toMe, rel) VALUES ('Act-ElRq-03', 'Act-ElRq-01', 1);
#################################################################################################
INSERT INTO chunk_rel (fromMC, toMC, fromME, toME) VALUES ('Chu-ReqEli-02', 'Chu-ReqEli-01', 'Act-ElRq-02', 'Act-ElRq-01');
INSERT INTO chunk_rel (fromMc, toMC, fromME, toME) VALUES ('Chu-ReqEli-03', 'Chu-ReqEli-01', 'Act-ElRq-03', 'Act-ElRq-01');
#################################################################################################
INSERT INTO activity_rel_type (id, name) VALUES (1, 'start-start');
INSERT INTO activity_rel_type (id, name) VALUES (2, 'end-start');
INSERT INTO activity_rel_type (id, name) VALUES (3, 'exclusion');
#################################################################################################
INSERT INTO artefact_rel_type (id, name) VALUES (1, 'constraint');
INSERT INTO artefact_rel_type (id, name) VALUES (2, 'in-sync');
