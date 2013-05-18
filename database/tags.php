<?
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertTag($tagname) {
        global $db;
        $errors = new DatabaseException();
        // TODO validateTag
        
        try {
            $stmt = $db->prepare("INSERT INTO tag (tagname) VALUES (?)");
            $stmt->execute(array($tagname));
            return $db->lastInsertId('tag_tagid_seq');
        } catch(Exception $e) {
            $errors->addError('tag', 'error processing insert into tag table');
            throw($errors);
        }
    }

    function addTagToQuestion($questionid, $tagid) {
        global $db;
        try {
            $stmt = $db->prepare("INSERT INTO questiontag (questionid, tagid) VALUES (?, ?)");
            $stmt->execute(array($questionid, $tagid));
        } catch(Exception $e) {
            $errors->addError('questiontag', 'error processing insert into questiontag table');
            throw($errors);
        }
    }

    function removeTagFromQuestion($questionid, $tagid) {
        global $db;
        try {
            $stmt = $db->prepare("DELETE FROM questiontag WHERE questionid = ? AND tagid = ?");
            $stmt->execute(array($questionid, $tagid));
        } catch(Exception $e) {
            $errors->addError('questiontag', 'error processing delete from questiontag table');
            throw($errors);
        }
    }

    function getTagByName($tagname) {
        global $db;
        $result = $db->prepare("SELECT * FROM tag WHERE tagname = ?");
        $result->execute(array($tagname));
        return $result->fetch();
    }

    function getTagsOfQuestion($questionid) {
        global $db;
        $result = $db->prepare("SELECT tag.* FROM tag, questiontag WHERE tag.tagid = questiontag.tagid AND questionid = ?");
        $result->execute(array($questionid));
        return $result->fetchAll();
    }
?>
